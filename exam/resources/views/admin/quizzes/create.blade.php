@extends('layouts.app')

@section('title', 'Создание теста')

@section('content')
    <div class="container">
        <h1>Создание нового теста</h1>

        <form action="{{ route('admin.quizzes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Название теста</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3">{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                       value="{{ old('time_limit') }}" min="1">
                @error('time_limit')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" id="is_published" class="form-check-input"
                       value="1" {{ old('is_published') ? 'checked' : '' }}>
                <label for="is_published" class="form-check-label">Опубликовать тест</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Создать тест
            </button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection
