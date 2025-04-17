@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">

        @if ($errors->any())
            <div class="alert alert-danger mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Приветствие с аватаром и датой -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/50' }}" alt="Аватар" class="w-12 h-12 rounded-full mr-4">
                <h1 class="text-3xl font-bold">Привет, {{ auth()->user()->name }}!</h1>
            </div>
            <span class="text-gray-500">{{ now()->format('d.m.Y') }}</span>
        </div>

        <!-- Статистика с иконками -->
        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg flex items-center">
                <i class="fas fa-book text-blue-500 text-3xl mr-4"></i>
                <div>
                    <h3 class="font-semibold text-lg">Всего викторин</h3>
                    <div class="text-3xl font-bold text-blue-600">{{ $availableQuizzes->count() }}</div>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg flex items-center">
                <i class="fas fa-check-circle text-green-500 text-3xl mr-4"></i>
                <div>
                    <h3 class="font-semibold text-lg">Пройдено викторин</h3>
                    <div class="text-3xl font-bold text-green-600">{{ $completedQuizzes->count() }}</div>
                </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg flex items-center">
                <i class="fas fa-star text-purple-500 text-3xl mr-4"></i>
                <div>
                    <h3 class="font-semibold text-lg">Общий балл</h3>
                    <div class="text-3xl font-bold text-purple-600">{{ $completedQuizzes->sum('score') ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Доступные викторины с кнопкой "Начать" и индикатором сложности -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Доступные викторины</h2>
                @if($availableQuizzes->count())
                    <div class="space-y-4">
                        @foreach($availableQuizzes as $quiz)
                            <div class="border-b pb-4 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('quizzes.show', $quiz->id) }}" class="text-blue-600 hover:underline font-medium">
                                        {{ $quiz->title }}
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($quiz->description, 100) }}</p>
                                    <span class="text-xs text-gray-400">Категория: {{ optional($quiz->category)->name ?? 'Без категории' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-4">Сложность: {{ $quiz->difficulty ?? 'Средняя' }}</span>
                                    <a href="{{ route('quizzes.start', $quiz->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Начать</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">Нет доступных викторин</p>
                @endif
            </div>

            <!-- История попыток в виде таблицы -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">История попыток</h2>
                @if($completedQuizzes->count())
                    <table class="min-w-full table-auto">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Викторина</th>
                            <th class="px-4 py-2 text-left">Баллы</th>
                            <th class="px-4 py-2 text-left">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($completedQuizzes as $attempt)
                            @php
                                $totalPoints = $attempt->quiz->questions->sum('points');
                                $percentage = $totalPoints > 0 ? ($attempt->score / $totalPoints) * 100 : 0;
                            @endphp
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $attempt->quiz->title }}</td>
                                <td class="px-4 py-2">
                                        <span class="{{ $percentage >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $attempt->score }} ({{ number_format($percentage, 1) }}%)
                                        </span>
                                </td>
                                <td class="px-4 py-2 text-gray-500">{{ $attempt->completed_at->format('d.m.Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-center">Вы ещё не проходили викторины</p>
                @endif
            </div>
        </div>
    </div>
@endsection
