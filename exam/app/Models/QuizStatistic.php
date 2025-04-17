<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizStatistic extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'quiz_statistics';

    /**
     * Поля, которые можно массово заполнять.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id',
        'total_attempts',
        'average_score',
        'max_score',
        'min_score',
    ];

    /**
     * Поля, которые должны быть приведены к определённым типам.
     *
     * @var array
     */
    protected $casts = [
        'total_attempts' => 'integer',
        'average_score' => 'float',
        'max_score' => 'integer',
        'min_score' => 'integer',
    ];

    /**
     * Отношение "belongs to" с моделью Quiz.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
