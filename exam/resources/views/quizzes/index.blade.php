@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Доступные тесты</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="mb-3">
                <a href="{{ route('quizzes.manage') }}" class="btn btn-secondary">Управление тестами</a>
            </div>
        @endif

        <div class="row">
            @forelse ($quizzes as $quiz)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $quiz->title }}</h5>
                            <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                            <p class="card-text">
                                <small class="text-muted">
                                    Категория: {{ $quiz->category->name }}<br>
                                    @if($quiz->time_limit)
                                        Ограничение по времени: {{ $quiz->time_limit }} мин.
                                    @else
                                        Без ограничения по времени
                                    @endif
                                </small>
                            </p>
                            <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-primary">Подробнее</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p>Доступных тестов пока нет.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
