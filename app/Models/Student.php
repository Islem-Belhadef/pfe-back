<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'department_id',
        'speciality_id',
        'semester',
        'level',
        'academic_year',
        'date_of_birth',
        'user_id',
        'phone_num',
        'student_card_num'
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
