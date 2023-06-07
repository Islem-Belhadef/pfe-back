<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'internship_id',
        'discipline',
        'aptitude',
        'initiative',
        'innovation',
        'acquired_knowledge',
        'note'
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
