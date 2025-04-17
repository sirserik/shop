<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Отображение личного кабинета пользователя
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Доступные опубликованные викторины
        $availableQuizzes = Quiz::where('is_published', true)
            ->with('category')
            ->get();

        // Завершенные попытки викторин текущего пользователя
        $completedQuizzes = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->with(['quiz' => function($query) {
                $query->with('category');
            }])
            ->latest('completed_at')
            ->get();

        return view('dashboard', [
            'availableQuizzes' => $availableQuizzes,
            'completedQuizzes' => $completedQuizzes
        ]);
    }
}
