<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
