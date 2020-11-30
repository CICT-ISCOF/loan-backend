<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    use HasFactory;

    protected $fillable = ['role', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeAdmin($query)
    {
        return $this->by($query, 'Admin');
    }

    public function scopeBookeeper($query)
    {
        return $this->by($query, 'Bookeeper');
    }

    public function scopeMember($query)
    {
        return $this->by($query, 'Member');
    }

    protected function by($query, $role)
    {
        return $query->where('role', $role);
    }
}
