<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        HasApiTokens,
        Searchable;

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
        'monthly_salary',
        'account_number',
        'net_pay',
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

    protected $appends = ['remaining_salary'];

    protected static function booted()
    {
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

    public static function notifyAdmins(Notification $notification)
    {
        $users = static::where('role', 'Super Admin')->get();
        NotificationFacade::send($users, $notification);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'position' => $this->position,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'account_number' => $this->account_number,
        ];
    }

    public function getRemainingSalaryAttribute()
    {
        $monthly = floatval($this->monthly_salary);
        foreach ($this->loans as $loan) {
            $monthly -= floatval($loan->amount);
        }
        return number_format($monthly, 0);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function confirmation()
    {
        return $this->morphOne(Confirmation::class, 'confirmable');
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
                return (int)$confirmation->confirmable_id;
            })
            ->toArray();
        return $query->whereIn('id', $ids)
            ->where('approved', true);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
