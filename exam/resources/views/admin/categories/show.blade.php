@extends('layouts.app')

@section('title', 'Категория: ' . $category->name)

@section('content')
    <div class="container">
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1>Категория: {{ $category->name }}</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Информация о категории</h5>
                <p><strong>Название:</strong> {{ $category->name }}</p>
                <p><strong>Описание:</strong> {{ $category->description ?? 'Нет описания' }}</p>
                <p><strong>Количество тестов:</strong> {{ $category->quizzes->count() }}</p>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Редактировать
                </a>
            </div>
        </div>

        <h2>Тесты в категории</h2>
        @if($category->quizzes->isEmpty())
            <div class="alert alert-info">Тестов в этой категории пока нет.</div>
        @else
            <div class="row">
                @foreach($category->quizzes as $quiz)
                    <div class="col-md-4 mb-4">
                        <div class="card quiz-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $quiz->title }}</h5>
                                <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Вопросов: {{ $quiz->questions->count() }}<br>
                                        @if($quiz->time_limit)
                                            Время: {{ $quiz->time_limit }} мин.
                                        @else
                                            Без ограничения времени
                                        @endif<br>
                                        Статус: {{ $quiz->is_published ? 'Опубликован' : 'Не опубликован' }}
                                    </small>
                                </p>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Редактировать
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
