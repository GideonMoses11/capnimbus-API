<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Deposit\DepositController;
use App\Http\Controllers\Withdrawal\WithdrawalController;
use App\Http\Controllers\Investment\InvestmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/logout', [AuthController::class, 'signout']);
    Route::get('/account', [AuthController::class, 'userAccount']);

    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/my-referrals', [AuthController::class, 'myReferrals']);
    Route::get('/my-referrer', [AuthController::class, 'myReferrer']);

    Route::post('/verify-kyc', [AuthController::class, 'verifyKYC']);
    Route::get('/my-kyc', [AuthController::class, 'myKYC']);
});


//deposit
Route::group(['prefix' => 'deposits'], function () {
    Route::post('', [DepositController::class, 'save']);
    Route::get('', [DepositController::class, 'index']);
    // Route::post('/{id}', [DepositController::class, 'update']);
    Route::get('/{id}', [DepositController::class, 'find']);
});


Route::group(['prefix' => 'investments'], function () {
    Route::post('/{id}', [InvestmentController::class, 'save']);
    Route::get('', [InvestmentController::class, 'index']);
    Route::get('/{id}', [InvestmentController::class, 'find']);
});

Route::group(['prefix' => 'withdrawals'], function () {
    Route::post('', [WithdrawalController::class, 'save']);
    Route::get('', [WithdrawalController::class, 'index']);
    Route::get('/{id}', [WithdrawalController::class, 'find']);
});



Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {

    Route::group(['prefix' => 'deposits'], function () {
        Route::get('', [AdminController::class, 'listDeposit']);
        Route::post('/{id}', [AdminController::class, 'updateDepositStatus']);
        Route::get('/{id}', [AdminController::class, 'showDeposit']);
    });

    Route::group(['prefix' => 'investments'], function () {
        Route::get('', [AdminController::class, 'listInvestment']);
        Route::post('/{id}', [AdminController::class, 'updateInvestmentStatus']);
        Route::get('/{id}', [AdminController::class, 'showInvestment']);
    });

    Route::group(['prefix' => 'withdrawals'], function () {
        Route::get('', [AdminController::class, 'listWithdrawal']);
        Route::post('/{id}', [AdminController::class, 'updateWithdrawalStatus']);
        Route::get('/{id}', [AdminController::class, 'showWithdrawal']);
    });

    Route::group(['prefix' => 'plans'], function () {
        Route::get('', [AdminController::class, 'listPlan']);
        Route::post('/add', [AdminController::class, 'addPlan']);
        Route::post('/{id}', [AdminController::class, 'updatePlan']);
        Route::post('/delete/{id}', [AdminController::class, 'deletePlan']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [AdminController::class, 'listUser']);
        Route::get('/{id}', [AdminController::class, 'showUser']);
    });

    Route::group(['prefix' => 'kycs'], function () {
        Route::get('', [AdminController::class, 'listKYC']);
        Route::post('/verify/{id}', [AdminController::class, 'updateKYC']);
    });

});
