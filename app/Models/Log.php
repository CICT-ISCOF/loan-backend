<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
    ];

    protected $with = ['user'];

    protected static function booted()
    {
        static::creating(function ($log) {
            $user = request()->user();
            $log->user_id = $user ? $user->id : null;
        });
    }

    public function loggable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
