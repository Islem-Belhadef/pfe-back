<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'duration',
        'status',
        'supervisor_email',
        'supervisor_first_name',
        'supervisor_last_name',
        'title',
        'student_id',
        'supervisor_id'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
