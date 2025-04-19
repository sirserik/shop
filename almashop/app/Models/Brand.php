<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Brand extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            $brand->slug = static::generateUniqueSlug($brand->name);
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name')) {
                $brand->slug = static::generateUniqueSlug($brand->name, $brand->id);
            }
        });
    }
    protected static function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Скоуп активных брендов
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
