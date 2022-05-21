<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Account\AccountController;
use App\Http\Controllers\Api\Transaction\TransactionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')
->group(function (){
    Route::post('accounts/type', [AccountController::class, 'type'])->name('accounts.type');
    Route::resource('accounts', AccountController::class)
        ->only([
            'store',
            'index'        
        ])
    ;
    


    Route::resource('users', UserController::class)
        ->only([
            'index'        
        ])
    ;

    Route::resource('transactions', TransactionController::class)
        ->only([
            'store',    
            'index',    
        ])
    ;
});