<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'content',
        'starts_at',
        'ends_at',
        'is_active',
        'discount_percent',
    ];

    protected $casts = [
        'starts_at'        => 'date',
        'ends_at'          => 'date',
        'is_active'        => 'boolean',
        'discount_percent' => 'integer',
    ];

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->toDateString();

        if ($this->starts_at && $this->starts_at->toDateString() > $today) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->toDateString() < $today) {
            return false;
        }

        return true;
    }

    public function patients()
    {
        return $this->belongsToMany(\App\Models\Patient::class, 'promotion_patient')
            ->withTimestamps();
    }
}