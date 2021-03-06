<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// -------jamel---------
use App\Http\Controllers\SavingTermsController;
use App\Http\Controllers\LoanTermsController;
use App\Http\Controllers\LoanApplicationsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserLoanController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SMSController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::resource('/sms', SMSController::class);
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware(['auth:sanctum', 'restrict:unapproved', 'restrict:unconfirmed'])->group(function () {
	Route::apiResources([
		'loans' => LoanController::class,
		'loans.payments' => PaymentController::class,
		'users' => UserController::class,
		'saving-terms' => SavingTermsController::class,
		'loan-terms' => LoanTermsController::class,
		'loan-applications' => LoanApplicationsController::class,
		'savings' => SavingsController::class,
		'withdrawals' => WithdrawalController::class,
	]);

	Route::apiResource('confirmations', ConfirmationController::class)->except(['store', 'destroy']);
	Route::apiResource('confirmations/loans', LoanApplicationController::class)->except(['store', 'destroy']);
	Route::get('/loans', UserLoanController::class);
	Route::get('/search/user', [SearchController::class, 'user']);
});
