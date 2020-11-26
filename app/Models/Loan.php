<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'type',
        'amount',
        'previous_amount',
        'net_amount',
        'interval',
        'charges',
        'terms',
        'comaker_id',
        'organization_id',
        'user_id',
    ];

    protected static function booted()
    {
        static::created(function ($loan) {
            $loan->confirmation()->create([
                'hash' => Str::random(5),
                'approved' => env('APP_ENV', false) === 'local' || (request()->user() && request()->user()->role === 'Super Admin') || env('APP_ENV', false) === 'testing',
            ]);
        });

        static::deleting(function ($user) {
            $user->confirmation->delete();
        });
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function comaker()
    {
        return $this->belongsTo(User::class, 'comaker_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmation()
    {
        return $this->morphOne(Confirmation::class, 'confirmable');
    }

    public function scopeConfirmed($query)
    {
        $ids = Confirmation::confirmed()
            ->loan()
            ->get()
            ->map(function ($confirmation) {
                return $confirmation->confirmable_id;
            })
            ->toArray();
        return $query->whereIn('id', $ids);
    }

    public function scopeNotConfirmed($query)
    {
        $ids = Confirmation::confirmed()
            ->loan()
            ->get()
            ->map(function ($confirmation) {
                return $confirmation->confirmable_id;
            })
            ->toArray();
        return $query->whereNotIn('id', $ids);
    }
}
