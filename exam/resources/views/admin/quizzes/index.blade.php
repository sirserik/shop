@extends('layouts.app')

@section('title', 'Управление тестами')

@section('content')
    <div class="container">
        <h1>Управление тестами</h1>

        <div class="mb-3">
            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Создать новый тест
            </a>
        </div>

        @if($quizzes->isEmpty())
            <div class="alert alert-info">Тестов пока нет. Создайте первый тест!</div>
        @else
            <div class="row">
                @foreach($quizzes as $quiz)
                    <div class="col-md-4 mb-4">
                        <div class="card quiz-card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $quiz->title }}</h5>
                                <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Категория: {{ $quiz->category->name }}<br>
                                        Вопросов: {{ $quiz->questions->count() }}<br>
                                        @if($quiz->time_limit)
                                            Время: {{ $quiz->time_limit }} мин.
                                        @else
                                            Без ограничения времени
                                        @endif<br>
                                        Статус: {{ $quiz->is_published ? 'Опубликован' : 'Не опубликован' }}
                                    </small>
                                </p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Редактировать
                                    </a>
                                    <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Вы уверены, что хотите удалить тест?');">
                                            <i class="fas fa-trash"></i> Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
