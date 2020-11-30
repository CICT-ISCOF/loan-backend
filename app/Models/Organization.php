<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'address',
        'number',
        'email',
    ];

    public function members()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
