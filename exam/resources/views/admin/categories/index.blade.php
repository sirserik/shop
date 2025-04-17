@extends('layouts.app')

@section('title', 'Управление категориями')

@section('content')
    <div class="container">
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Управление категориями</li>
            </ol>
        </nav>

        <h1>Управление категориями</h1>

        <div class="mb-3">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Создать новую категорию
            </a>
        </div>

        @if($categories->isEmpty())
            <div class="alert alert-info">Категорий пока нет. Создайте первую категорию!</div>
        @else
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Тестов</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>{{ $category->quizzes_count }}</td>
                        <td>
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Вы уверены, что хотите удалить категорию?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Пагинация -->
            {{ $categories->links() }}
        @endif
    </div>
@endsection
