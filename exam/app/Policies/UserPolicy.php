<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Определяет, может ли пользователь просматривать список пользователей.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Определяет, может ли пользователь просматривать конкретного пользователя.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $target
     * @return bool
     */
    public function view(User $user, User $target)
    {
        // Админ может видеть всех, обычный пользователь — только себя
        return $user->role === 'admin' || $user->id === $target->id;
    }

    /**
     * Определяет, может ли пользователь создавать пользователей.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Определяет, может ли пользователь обновлять данные другого пользователя.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $target
     * @return bool
     */
    public function update(User $user, User $target)
    {
        // Админ может обновлять всех, обычный пользователь — только себя
        return $user->role === 'admin' || $user->id === $target->id;
    }

    /**
     * Определяет, может ли пользователь удалять другого пользователя.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $target
     * @return bool
     */
    public function delete(User $user, User $target)
    {
        // Только админ может удалять, и не себя
        return $user->role === 'admin' && $user->id !== $target->id;
    }

    /**
     * Определяет, может ли пользователь управлять викторинами (например, создавать или публиковать).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function manageQuizzes(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Определяет, может ли пользователь видеть статистику викторин.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewQuizStatistics(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Определяет, может ли пользователь проходить викторины.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function takeQuizzes(User $user)
    {
        return in_array($user->role, ['user', 'admin']);
    }
}
