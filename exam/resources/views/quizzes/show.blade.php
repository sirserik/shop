@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Тесты</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $quiz->title }}</li>
            </ol>
        </nav>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>{{ $quiz->title }}</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Информация о тесте</h5>
                <p class="card-text">{{ $quiz->description ?? 'Описание отсутствует' }}</p>
                <p class="card-text">
                    <strong>Категория:</strong> {{ $quiz->category->name }}<br>
                    <strong>Вопросов:</strong> {{ $quiz->questions->count() }}<br>
                    <strong>Ограничение по времени:</strong>
                    @if($quiz->time_limit)
                        {{ $quiz->time_limit }} минут
                    @else
                        Без ограничения
                    @endif
                </p>

                @auth
                    <form action="{{ route('quizzes.start', $quiz->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Начать тест
                        </button>
                    </form>
                @else
                    <p class="text-muted">Войдите, чтобы начать тест.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Войти
                    </a>
                @endauth
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Вернуться к списку тестов
        </a>
    </div>
@endsection
