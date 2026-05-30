<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App as AppFacade;

#[Fillable([
    'code',
    'full_name',
    'occupation',
    'birth_date',
    'photo',
    'issue_date',
    'expiry_date',
])]
class VerificationCard extends Model
{
    use HasFactory;

    protected $casts = [
        'birth_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function displayName(string $locale = null): string
    {
        return $this->full_name;
    }

    public function displayOccupation(string $locale = null): string
    {
        return $this->occupation;
    }
}
