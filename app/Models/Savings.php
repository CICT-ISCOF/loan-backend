<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'amount',      
    ];

    public function organization(){
		return $this->belongsTo(Organization::class, 'organization_id', 'id');
	}
}
