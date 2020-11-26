<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    use HasFactory;

    protected $fillable = ['hash', 'approved'];
    protected $with = ['confirmable'];

    public function confirmable()
    {
        return $this->morphTo();
    }

    public function scopeConfirmed($query)
    {
        return $query->where('approved', true);
    }

    public function scopeUser($query)
    {
        return $query->where('confirmable_type', User::class);
    }

    public function scopeLoan($query)
    {
        return $query->where('confirmable_type', Loan::class);
    }
}
