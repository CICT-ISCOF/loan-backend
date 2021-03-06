<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

class LoanTerms extends Model
{
	use HasFactory;

	protected $fillable = [
		'plan_name',
		'loan_type',
		'interval',
		'amount',
		'total_gain',
		'description',
		'interest_in_percent',
		'penalty_per_interval',
	];
}
