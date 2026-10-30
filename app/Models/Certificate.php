<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['number', 'recipient_name', 'title', 'description', 'issuer', 'image_path', 'issue_date', 'expiry_date', 'is_revoked'])]
class Certificate extends Model
{
    protected $hidden = ['verification_token'];

    protected function casts(): array
    {
        return ['issue_date' => 'date', 'expiry_date' => 'date', 'is_revoked' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate) {
            $certificate->verification_token = Str::random(48);
        });
    }

    public function status(): string
    {
        if ($this->is_revoked) {
            return 'revoked';
        }
        if ($this->issue_date->isAfter(today())) {
            return 'pending';
        }

        return $this->expiry_date && $this->expiry_date->isBefore(today()) ? 'expired' : 'valid';
    }
}
