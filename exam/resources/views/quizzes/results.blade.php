@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Результаты теста "{{ $attempt->quiz->title }}"</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Общий результат</h5>
                <p>Набрано баллов: {{ $attempt->score }} из {{ $maxScore }}</p>
                <p>Процент правильных ответов: {{ number_format($percentage, 1) }}%</p>
                <p>
                    Время начала: {{ $attempt->started_at->format('d.m.Y H:i:s') }}<br>
                    Время завершения: {{ $attempt->completed_at->format('d.m.Y H:i:s') }}<br>
                    Затраченное время: {{ $attempt->started_at->diff($attempt->completed_at)->format('%H:%I:%S') }}
                </p>

                <div class="progress">
                    <div class="progress-bar {{ $percentage >= 70 ? 'bg-success' : ($percentage >= 40 ? 'bg-warning' : 'bg-danger') }}"
                         role="progressbar"
                         style="width: {{ $percentage }}%"
                         aria-valuenow="{{ $percentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        {{ number_format($percentage, 1) }}%
                    </div>
                </div>
            </div>
        </div>

        <h3>Детализация по вопросам</h3>

        @php
            // Группируем ответы пользователя по идентификатору вопроса.
            $userAnswersByQuestion = $attempt->userAnswers->groupBy('question_id');
        @endphp

        @foreach($attempt->quiz->questions as $index => $question)
            <div class="card mb-3">
                <div class="card-header">
                    Вопрос {{ $index + 1 }} ({{ $question->points }} балл.)
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $question->question_text }}</h5>

                    @if($question->media_url)
                        <div class="mb-3">
                            <img src="{{ $question->media_url }}" alt="Question Media" class="img-fluid" style="max-width: 300px;">
                        </div>
                    @endif

                    @if($question->question_type == 'text')
                        <p>Ваш ответ:</p>
                        <div class="alert alert-secondary">
                            {{-- Используем helper optional для избежания ошибки, если ответ отсутствует --}}
                            {{ optional(optional($userAnswersByQuestion->get($question->id))->first())->text_answer ?? 'Нет ответа' }}
                        </div>
                        <p><em>Текстовые ответы оцениваются вручную.</em></p>
                    @else
                        <p>Правильные ответы:</p>
                        <ul>
                            @foreach($question->answers->where('is_correct', true) as $answer)
                                <li>
                                    {{ $answer->answer_text }}
                                    @if($answer->media_url)
                                        <br><img src="{{ $answer->media_url }}" alt="Answer Media" class="img-fluid" style="max-width: 100px;">
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <p>Ваши ответы:</p>
                        @php
                            // Если для вопроса нет ответа, используем пустую коллекцию.
                            $userAnswerIds = $userAnswersByQuestion->get($question->id, collect())->pluck('answer_id')->toArray();
                        @endphp

                        @foreach($question->answers as $answer)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       {{ in_array($answer->id, $userAnswerIds) ? 'checked' : '' }}
                                       disabled>
                                <label class="form-check-label {{ $answer->is_correct ? 'text-success' : '' }}">
                                    {{ $answer->answer_text }}
                                    @if($answer->media_url)
                                        <br><img src="{{ $answer->media_url }}" alt="Answer Media" class="img-fluid" style="max-width: 100px;">
                                    @endif
                                    {{-- Выводим иконки для наглядности --}}
                                    @if($answer->is_correct && in_array($answer->id, $userAnswerIds))
                                        <i class="fas fa-check text-success"></i>
                                    @elseif(!$answer->is_correct && in_array($answer->id, $userAnswerIds))
                                        <i class="fas fa-times text-danger"></i>
                                    @elseif($answer->is_correct && !in_array($answer->id, $userAnswerIds))
                                        <i class="fas fa-exclamation text-warning"></i>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach

        <div class="mt-3">
            <a href="{{ route('quizzes.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Вернуться к списку тестов
            </a>
            <form action="{{ route('quizzes.start', $attempt->quiz->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-redo me-2"></i>Пройти заново
                </button>
            </form>
        </div>
    </div>
@endsection
