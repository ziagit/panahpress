<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'verification_card_id',
    'path',
    'sort_order',
])]
class VerificationCardPhoto extends Model
{
    use HasFactory;

    public function verificationCard(): BelongsTo
    {
        return $this->belongsTo(VerificationCard::class);
    }
}
