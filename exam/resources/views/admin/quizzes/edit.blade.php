@extends('layouts.app')

@section('title', 'Редактирование теста: ' . $quiz->title)

@section('content')
    <div class="container">
        <h1>Редактирование теста: {{ $quiz->title }}</h1>

        <!-- Форма редактирования теста -->
        <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" class="mb-5">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Название теста</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $quiz->title) }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3">{{ old('description', $quiz->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $quiz->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="time_limit" class="form-label">Ограничение по времени (в минутах, опционально)</label>
                <input type="number" name="time_limit" id="time_limit" class="form-control @error('time_limit') is-invalid @enderror"
                       value="{{ old('time_limit', $quiz->time_limit) }}" min="1">
                @error('time_limit')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" id="is_published" class="form-check-input"
                       value="1" {{ old('is_published', $quiz->is_published) ? 'checked' : '' }}>
                <label for="is_published" class="form-check-label">Опубликовать тест</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Сохранить изменения
            </button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">Назад</a>
        </form>

        <!-- Список существующих вопросов -->
        <h2>Вопросы</h2>
        @if($quiz->questions->isEmpty())
            <div class="alert alert-info">Вопросов пока нет. Добавьте первый вопрос!</div>
        @else
            @foreach($quiz->questions as $index => $question)
                <div class="card mb-3 question-card">
                    <div class="card-header">
                        Вопрос {{ $index + 1 }} ({{ $question->points }} балл.)
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $question->question_text }}</h5>
                        @if($question->media_url)
                            <img src="{{ $question->media_url }}" alt="Question Media" class="img-fluid mb-3" style="max-width: 300px;">
                        @endif
                        <p>Тип: {{ $question->question_type === 'single' ? 'Одиночный выбор' : ($question->question_type === 'multiple' ? 'Множественный выбор' : 'Текстовый') }}</p>
                        @if($question->question_type !== 'text')
                            <p>Варианты ответа:</p>
                            <ul>
                                @foreach($question->answers as $answer)
                                    <li class="{{ $answer->is_correct ? 'text-success' : '' }}">
                                        {{ $answer->answer_text }}
                                        @if($answer->media_url)
                                            <br><img src="{{ $answer->media_url }}" alt="Answer Media" class="img-fluid" style="max-width: 100px;">
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <form action="{{ route('admin.quizzes.removeQuestion', [$quiz->id, $question->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Вы уверены, что хотите удалить этот вопрос?');">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Форма добавления нового вопроса -->
        <h2>Добавить вопрос</h2>
        <form action="{{ route('admin.quizzes.addQuestion', $quiz->id) }}" method="POST" id="add-question-form">
            @csrf

            <div class="mb-3">
                <label for="question_text" class="form-label">Текст вопроса</label>
                <input type="text" name="question_text" id="question_text" class="form-control @error('question_text') is-invalid @enderror"
                       value="{{ old('question_text') }}" required>
                @error('question_text')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="question_type" class="form-label">Тип вопроса</label>
                <select name="question_type" id="question_type" class="form-control @error('question_type') is-invalid @enderror" required>
                    <option value="single" {{ old('question_type') === 'single' ? 'selected' : '' }}>Одиночный выбор</option>
                    <option value="multiple" {{ old('question_type') === 'multiple' ? 'selected' : '' }}>Множественный выбор</option>
                    <option value="text" {{ old('question_type') === 'text' ? 'selected' : '' }}>Текстовый ответ</option>
                </select>
                @error('question_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="points" class="form-label">Баллы</label>
                <input type="number" name="points" id="points" class="form-control @error('points') is-invalid @enderror"
                       value="{{ old('points', 1) }}" min="1" max="100" required>
                @error('points')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="media_url" class="form-label">URL медиа (опционально)</label>
                <input type="url" name="media_url" id="media_url" class="form-control @error('media_url') is-invalid @enderror"
                       value="{{ old('media_url') }}">
                @error('media_url')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="answers-container" class="mb-3" @if(old('question_type') === 'text') style="display: none;" @endif>
                <label class="form-label">Варианты ответа (минимум 2 для вопросов с выбором)</label>
                <div id="answers-list">
                    @for($i = 0; $i < max(2, old('answers', []) ? count(old('answers')) : 2); $i++)
                        <div class="input-group mb-2 answer-group">
                            <input type="text" name="answers[{{ $i }}][text]"
                                   class="form-control @error('answers.' . $i . '.text') is-invalid @enderror"
                                   value="{{ old('answers.' . $i . '.text') }}" placeholder="Текст ответа">
                            <input type="checkbox" name="answers[{{ $i }}][is_correct]" value="1"
                                   class="form-check-input mx-2" {{ old('answers.' . $i . '.is_correct') ? 'checked' : '' }}>
                            <input type="url" name="answers[{{ $i }}][media_url]"
                                   class="form-control @error('answers.' . $i . '.media_url') is-invalid @enderror"
                                   value="{{ old('answers.' . $i . '.media_url') }}" placeholder="URL медиа (опционально)">
                            <button type="button" class="btn btn-outline-danger remove-answer"
                                    @if($i < 2) disabled @endif>
                                <i class="fas fa-trash"></i>
                            </button>
                            @error('answers.' . $i . '.text')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endfor
                </div>
                <button type="button" class="btn btn-outline-primary" id="add-answer">
                    <i class="fas fa-plus"></i> Добавить вариант ответа
                </button>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Добавить вопрос
            </button>
        </form>
    </div>

    @push('scripts')
        <script>
            // Показ/скрытие вариантов ответа в зависимости от типа вопроса
            document.getElementById('question_type').addEventListener('change', function() {
                document.getElementById('answers-container').style.display = this.value === 'text' ? 'none' : 'block';
            });

            // Динамическое добавление вариантов ответа
            let answerIndex = {{ old('answers', []) ? count(old('answers')) : 2 }};
            document.getElementById('add-answer').addEventListener('click', function() {
                const container = document.getElementById('answers-list');
                const newAnswer = document.createElement('div');
                newAnswer.className = 'input-group mb-2 answer-group';
                newAnswer.innerHTML = `
                    <input type="text" name="answers[${answerIndex}][text]" class="form-control" placeholder="Текст ответа">
                    <input type="checkbox" name="answers[${answerIndex}][is_correct]" value="1" class="form-check-input mx-2">
                    <input type="url" name="answers[${answerIndex}][media_url]" class="form-control" placeholder="URL медиа (опционально)">
                    <button type="button" class="btn btn-outline-danger remove-answer">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                container.appendChild(newAnswer);
                answerIndex++;
            });

            // Удаление вариантов ответа
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-answer')) {
                    const answerGroups = document.querySelectorAll('.answer-group');
                    if (answerGroups.length > 2) {
                        e.target.closest('.answer-group').remove();
                    }
                }
            });
        </script>
    @endpush
@endsection
