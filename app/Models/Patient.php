<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'middle_name',
        'snils',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function analyses()
    {
        return $this->hasMany(Analysis::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(\App\Models\Promotion::class, 'promotion_patient')
            ->withTimestamps();
    }

    public function getActiveDiscountPercent(): ?int
    {
        $today = now()->toDateString();

        $promotion = $this->promotions()
            ->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $today);
            })
            ->whereNotNull('discount_percent')
            ->orderByDesc('discount_percent')
            ->first();

        return $promotion?->discount_percent;
    }
}