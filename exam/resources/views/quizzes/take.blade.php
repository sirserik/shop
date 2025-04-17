@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $quiz->title }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($timeLeft !== null)
            <div class="alert alert-info timer-wrapper">
                Оставшееся время: <span id="timer">{{ gmdate('H:i:s', $timeLeft) }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        <form action="{{ route('quizzes.submit', ['attempt_id' => $attempt->id]) }}" method="POST" id="quiz-form">
            @csrf

            @foreach($quiz->questions as $index => $question)
                <div class="card mb-4">
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
                            <div class="form-group">
                                <textarea class="form-control @error('answers.' . $question->id) is-invalid @enderror"
                                          name="answers[{{ $question->id }}]"
                                          rows="3">{{ old('answers.' . $question->id) }}</textarea>
                                @error('answers.' . $question->id)
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @elseif($question->question_type == 'single')
                            @foreach($question->answers as $answer)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="answers[{{ $question->id }}]"
                                           id="answer-{{ $answer->id }}"
                                           value="{{ $answer->id }}"
                                        {{ old('answers.' . $question->id) == $answer->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer-{{ $answer->id }}">
                                        {{ $answer->answer_text }}
                                        @if($answer->media_url)
                                            <br><img src="{{ $answer->media_url }}" alt="Answer Media" class="img-fluid" style="max-width: 100px;">
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                            @error('answers.' . $question->id)
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        @elseif($question->question_type == 'multiple')
                            @foreach($question->answers as $answer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="answers[{{ $question->id }}][]"
                                           id="answer-{{ $answer->id }}"
                                           value="{{ $answer->id }}"
                                        {{ in_array($answer->id, old('answers.' . $question->id, [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer-{{ $answer->id }}">
                                        {{ $answer->answer_text }}
                                        @if($answer->media_url)
                                            <br><img src="{{ $answer->media_url }}" alt="Answer Media" class="img-fluid" style="max-width: 100px;">
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                            @error('answers.' . $question->id)
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary btn-lg">Завершить тест</button>
        </form>
    </div>

    @if($timeLeft !== null)
        <script>
            let timeLeft = {{ $timeLeft }};
            let isSubmitted = false;
            let timerId = null;

            function updateTimer() {
                if (isSubmitted) {
                    return;
                }

                if (timeLeft <= 0) {
                    isSubmitted = true;
                    clearInterval(timerId);
                    document.getElementById('quiz-form').submit();
                    return;
                }

                const hours = Math.floor(timeLeft / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;

                document.getElementById('timer').textContent =
                    (hours < 10 ? '0' + hours : hours) + ':' +
                    (minutes < 10 ? '0' + minutes : minutes) + ':' +
                    (seconds < 10 ? '0' + seconds : seconds);

                timeLeft--;
            }

            if (timeLeft > 0) {
                timerId = setInterval(updateTimer, 1000); // Запуск таймера с интервалом 1 секунда
                updateTimer(); // Немедленное обновление для первой секунды
            } else if (timeLeft <= 0) {
                document.getElementById('timer').textContent = '00:00:00';
                // Здесь можно добавить сообщение о завершении теста
            }

            document.getElementById('quiz-form').addEventListener('submit', function(event) {
                if (isSubmitted) {
                    event.preventDefault();
                } else {
                    isSubmitted = true;
                    clearInterval(timerId);
                }
            });
        </script>
    @endif
@endsection
