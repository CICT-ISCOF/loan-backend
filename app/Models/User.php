<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',

        'position',

        'first_name',
        'last_name',
        'address',
        'number',

        'role',
        'approved',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $number = rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
            $user->account_number = $number;
        });

        static::created(function ($user) {
            $user->confirmation()->create([
                'hash' => Str::random(5),
                'approved' => env('APP_ENV', false) === 'local' || (request()->user() && request()->user()->role === 'Super Admin') || env('APP_ENV') === 'testing',
            ]);
        });

        static::deleting(function ($user) {
            $user->confirmation->delete();
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function confirmation()
    {
        return $this->morphOne(Confirmation::class, 'confirmable');
    }

    public function memberships()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function isAdmin()
    {
        return $this->role === 'Super Admin';
    }

    public function scopeConfirmed($query)
    {
        $ids = Confirmation::confirmed()
            ->user()
            ->get()
            ->map(function ($confirmation) {
                return $confirmation->confirmable_id;
            })
            ->toArray();
        return $query->whereIn('id', $ids)
            ->where('approved', true);
    }
}
