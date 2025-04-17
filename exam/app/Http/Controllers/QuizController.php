<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use App\Models\QuizStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuizController extends Controller
{
    public function __construct()
    {
        // Можно добавить middleware или другие настройки, если необходимо
    }

    // Отображение списка доступных тестов
    public function index()
    {
        try {
            $quizzes = Quiz::where('is_published', true)
                ->with('category')
                ->get();

            return view('quizzes.index', compact('quizzes'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при отображении списка тестов: ' . $e->getMessage()]);
        }
    }

    // Отображение подробной информации о тесте
    public function show($id)
    {
        try {
            $quiz = Quiz::where('is_published', true)
                ->with('category')
                ->findOrFail($id);

            return view('quizzes.show', compact('quiz'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Тест не найден.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при отображении теста: ' . $e->getMessage()]);
        }
    }

    // Начало прохождения теста
    public function start(Request $request, $id)
    {
        try {
            $quiz = Quiz::where('id', $id)
                ->where('is_published', true)
                ->firstOrFail();

            $attempt = QuizAttempt::create([
                'user_id'    => Auth::id(),
                'quiz_id'    => $quiz->id,
                'started_at' => now('UTC'),
            ]);

            $redirectUrl = route('quizzes.take', ['id' => $quiz->id, 'attempt_id' => $attempt->id]);

            if ($request->wantsJson()) {
                return response()->json(['redirect' => $redirectUrl]);
            }

            return redirect($redirectUrl);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Тест не найден.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при начале теста: ' . $e->getMessage()]);
        }
    }

    // Отображение вопросов теста
    public function take($id, $attempt_id)
    {
        try {
            $quiz = Quiz::where('is_published', true)
                ->with(['questions' => function ($query) {
                    $query->with('answers');
                }])
                ->findOrFail($id);

            $attempt = QuizAttempt::where('id', $attempt_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $timeLeft = null;

            if ($quiz->time_limit) {
                $endTime = $attempt->started_at->copy()->addMinutes($quiz->time_limit);
                if (now('UTC')->greaterThan($endTime)) {
                    $this->calculateResults($attempt);
                    return redirect()->route('quizzes.results', ['attempt_id' => $attempt->id])
                        ->with('warning', 'Время выполнения теста истекло.');
                }
                $timeLeft = $endTime->diffInSeconds(now('UTC'));
            }

            return view('quizzes.take', compact('quiz', 'attempt', 'timeLeft'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Тест или попытка не найдены.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при отображении теста: ' . $e->getMessage()]);
        }
    }

    // Сохранение ответов
    public function submit(Request $request, $attempt_id)
    {
        try {
            $attempt = QuizAttempt::where('id', $attempt_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($attempt->completed_at) {
                return redirect()->route('quizzes.results', ['attempt_id' => $attempt->id]);
            }

            $quiz = $attempt->quiz;

            if ($quiz->time_limit) {
                $endTime = $attempt->started_at->copy()->addMinutes($quiz->time_limit);
                if (now('UTC')->greaterThan($endTime)) {
                    $this->calculateResults($attempt);
                    return redirect()->route('quizzes.results', ['attempt_id' => $attempt->id])
                        ->with('warning', 'Время выполнения теста истекло, ответы не приняты.');
                }
            }

            // Валидация ответов (все ответы обязательны, настройте под свои нужды)
            $rules = [];
            foreach ($quiz->questions as $question) {
                $rules["answers.{$question->id}"] = 'required';
            }
            $request->validate($rules);

            DB::transaction(function () use ($request, $attempt) {
                $answers = $request->input('answers', []);
                foreach ($answers as $questionId => $answer) {
                    $attributes = ['attempt_id' => $attempt->id, 'question_id' => $questionId];
                    if (is_array($answer)) {
                        foreach ($answer as $answerId) {
                            UserAnswer::create($attributes + ['answer_id' => $answerId]);
                        }
                    } elseif (is_numeric($answer)) {
                        UserAnswer::create($attributes + ['answer_id' => $answer]);
                    } else {
                        UserAnswer::create($attributes + ['text_answer' => $answer]);
                    }
                }
            });

            $this->calculateResults($attempt);

            return redirect()->route('quizzes.results', ['attempt_id' => $attempt->id]);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Попытка не найдена.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при сохранении ответов: ' . $e->getMessage()]);
        }
    }

    // Расчет результатов теста
    private function calculateResults(QuizAttempt $attempt)
    {
        try {
            return DB::transaction(function () use ($attempt) {
                $quiz = $attempt->quiz()->with('questions.answers')->firstOrFail();
                $totalScore = 0;

                foreach ($quiz->questions as $question) {
                    $userAnswers = $attempt->userAnswers()
                        ->where('question_id', $question->id)
                        ->pluck('answer_id')
                        ->toArray();

                    if ($question->question_type === 'text') {
                        // Логика для текстовых ответов может быть добавлена здесь
                        continue;
                    }

                    $correctAnswers = $question->answers()
                        ->where('is_correct', true)
                        ->pluck('id')
                        ->toArray();

                    if ($question->question_type === 'single') {
                        $userAnswer = reset($userAnswers);
                        if (in_array($userAnswer, $correctAnswers)) {
                            $totalScore += $question->points;
                        }
                    } elseif ($question->question_type === 'multiple') {
                        $userAnswerIds = $userAnswers;
                        $allCorrect = count(array_diff($correctAnswers, $userAnswerIds)) === 0 &&
                            count(array_diff($userAnswerIds, $correctAnswers)) === 0;
                        if ($allCorrect) {
                            $totalScore += $question->points;
                        }
                    }
                }

                $attempt->update([
                    'completed_at' => now('UTC'),
                    'score'        => $totalScore,
                ]);

                // Обновление статистики только если тест завершен вовремя
                if (!$quiz->time_limit || $attempt->completed_at <= $attempt->started_at->copy()->addMinutes($quiz->time_limit)) {
                    $this->updateQuizStatistics($quiz, $totalScore);
                }

                return $totalScore;
            });
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Тест не найден.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при расчете результатов: ' . $e->getMessage()]);
        }
    }

    // Обновление статистики викторины
    private function updateQuizStatistics(Quiz $quiz, int $score)
    {
        try {
            $stats = QuizStatistic::firstOrCreate(
                ['quiz_id' => $quiz->id],
                [
                    'total_attempts' => 0,
                    'average_score'  => 0,
                    'max_score'      => 0,
                    'min_score'      => null,
                ]
            );

            $stats->increment('total_attempts');
            $stats->max_score = max($stats->max_score ?? 0, $score);
            $stats->min_score = is_null($stats->min_score) ? $score : min($stats->min_score, $score);
            $totalAttempts = $stats->total_attempts;
            $stats->average_score = (($stats->average_score * ($totalAttempts - 1)) + $score) / $totalAttempts;
            $stats->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при обновлении статистики: ' . $e->getMessage()]);
        }
    }

    // Отображение результатов теста
    public function results($attempt_id)
    {
        try {
            $attempt = QuizAttempt::where('id', $attempt_id)
                ->where('user_id', Auth::id())
                ->with([
                    'quiz.questions' => function ($query) {
                        $query->with('answers');
                    },
                    'userAnswers'
                ])
                ->firstOrFail();

            if (!$attempt->completed_at) {
                $this->calculateResults($attempt);
                $attempt->refresh();
            }

            $maxScore   = $attempt->quiz->questions->sum('points');
            $percentage = $maxScore > 0 ? ($attempt->score / $maxScore) * 100 : 0;

            return view('quizzes.results', compact('attempt', 'maxScore', 'percentage'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Попытка не найдена.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при отображении результатов: ' . $e->getMessage()]);
        }
    }
}
