<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{

    // Отображение списка тестов
    public function index()
    {
        $quizzes = Quiz::with('category')->get();
        return view('admin.quizzes.index', compact('quizzes'));
    }

    // Отображение формы для создания нового теста
    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    // Сохранение нового теста
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'time_limit' => 'nullable|integer|min:1',
            'is_published' => 'sometimes|boolean', // Исправлено
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.edit', $quiz->id)
            ->with('success', 'Тест успешно создан. Теперь добавьте вопросы.');
    }

    // Отображение формы для редактирования теста
    public function edit($id)
    {
        $quiz = Quiz::with(['questions' => function ($query) {
            $query->with('answers');
        }])->findOrFail($id);
        $categories = Category::all();

        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    // Обновление информации о тесте
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'time_limit' => 'nullable|integer|min:1',
            'is_published' => 'sometimes|boolean', // Исправлено
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.edit', $quiz->id)
            ->with('success', 'Информация о тесте обновлена.');
    }

    // Удаление теста
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Тест успешно удалён.');
    }

    // Добавление вопроса к тесту
    public function addQuestion(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);

        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:single,multiple,text', // Исправлено
            'points' => 'required|integer|min:1|max:100',
            'media_url' => 'nullable|url|max:255',
            'answers' => 'required_if:question_type,single,multiple|array|min:2', // Исправлено
            'answers.*.text' => 'required|string|max:500',
            'answers.*.is_correct' => 'required_if:question_type,single,multiple|boolean', // Исправлено
            'answers.*.media_url' => 'nullable|url|max:255',
        ], [
            'question_text.required' => 'Текст вопроса обязателен.',
            'question_type.required' => 'Выберите тип вопроса.',
            'points.required' => 'Укажите количество баллов.',
            'points.min' => 'Баллы должны быть больше 0.',
            'answers.required_if' => 'Для вопросов с выбором ответа добавьте минимум 2 варианта.',
            'answers.*.text.required' => 'Текст ответа обязателен.',
        ]);

        try {
            DB::transaction(function () use ($quiz, $validated) {
                $question = Question::create([
                    'question_text' => $validated['question_text'],
                    'quiz_id' => $quiz->id,
                    'points' => $validated['points'],
                    'question_type' => $validated['question_type'],
                    'media_url' => $validated['media_url'] ?? null,
                ]);

                if ($validated['question_type'] !== 'text' && isset($validated['answers'])) {
                    foreach ($validated['answers'] as $answerData) {
                        Answer::create([
                            'answer_text' => $answerData['text'],
                            'question_id' => $question->id,
                            'is_correct' => $answerData['is_correct'] ?? false,
                            'media_url' => $answerData['media_url'] ?? null,
                        ]);
                    }

                    if (!$question->answers()->where('is_correct', true)->exists()) {
                        throw new \Exception('Для вопросов с выбором ответа должен быть хотя бы один правильный ответ.');
                    }
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.quizzes.edit', $quiz->id)
                ->with('error', $e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.quizzes.edit', $quiz->id)
            ->with('success', 'Вопрос успешно добавлен.');
    }

    // Удаление вопроса
    public function removeQuestion($quiz_id, $question_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Question::where('id', $question_id)
            ->where('quiz_id', $quiz_id)
            ->firstOrFail();

        DB::transaction(function () use ($question) {
            $question->delete();
        });

        return redirect()->route('admin.quizzes.edit', $quiz->id)
            ->with('success', 'Вопрос успешно удалён.');
    }
}
