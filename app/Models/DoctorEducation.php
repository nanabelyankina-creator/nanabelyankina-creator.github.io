<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorEducation extends Model
{
    use HasFactory;

    protected $table = 'doctor_educations';

    protected $fillable = [
        'doctor_id',
        'type',
        'institution',
        'year',
        'specialty',
        'sort_order',
    ];

    public const TYPE_UNIVERSITY = 'university';
    public const TYPE_RESIDENCY = 'residency';
    public const TYPE_COURSES = 'courses';

    public static function types(): array
    {
        return [
            self::TYPE_UNIVERSITY => 'ВУЗ',
            self::TYPE_RESIDENCY => 'Ординатура',
            self::TYPE_COURSES => 'Курсы повышения квалификации',
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
