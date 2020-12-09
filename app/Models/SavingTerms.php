<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingTerms extends Model
{
    use HasFactory;

    protected $fillable = [
        'interests_per_year',
        'charges_per_transaction',
    ];
}
