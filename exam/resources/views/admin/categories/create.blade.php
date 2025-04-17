@extends('layouts.app')

@section('title', 'Создание категории')

@section('content')
    <div class="container">
        <!-- Хлебные крошки -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
                <li class="breadcrumb-item active" aria-current="page">Создание категории</li>
            </ol>
        </nav>

        <h1>Создание новой категории</h1>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название категории</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')
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

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Создать категорию
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Назад</a>
        </form>
    </div>
@endsection
