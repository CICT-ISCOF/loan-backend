<?php

namespace App\Models;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory, HasLogs;

    protected $fillable = [
        'name',
        'website',
        'address',
        'number',
        'email',
    ];

    protected $appends = [
        'counts',
    ];

    public function getCountsAttribute()
    {
        return [
            'members' => $this->members()
                ->member()
                ->count(),
            'admins' => $this->members()
                ->admin()
                ->count(),
            'bookeepers' => $this->members()
                ->bookeeper()
                ->count(),
            'staff' => $this->members()
                ->staff()
                ->count(),
        ];
    }

    public function members()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
