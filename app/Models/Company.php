<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_email',
        'address',
        'logo_link',
        'description',
    ];

    public $timestamps = false;

    public function supervisors(): HasMany
    {
        return $this->hasMany(Supervisor::class);
    }
}
