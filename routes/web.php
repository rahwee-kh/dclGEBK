<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web3LoginController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/','login');
/**
 * Customiz auth
 */
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('sign-user', [AuthController::class, 'message'])->name('sign-user');
Route::post('auth-user', [AuthController::class, 'verify'])->name('auth-user');
Route::get('account', [AuthController::class, 'dashboard'])->name('dashboard'); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('records', [AuthController::class, 'records'])->name('records'); 
Route::get('users', [AuthController::class, 'users'])->name('users'); 
Route::get('approve/{id}',[AuthController::class,'approve'])->name('user.approve');
Route::get('upgrade/{id}',[AuthController::class,'upgrade'])->name('user.upgrade');
Route::get('block/{id}',[AuthController::class,'block'])->name('user.block');
Route::get('redeem4/{id}',[AuthController::class,'redeem4'])->name('user.redeem4');
Route::get('redeemVip/{id}',[AuthController::class,'redeemVip'])->name('user.redeemVip');
Route::get('matchs', [AuthController::class, 'matchs'])->name('matchs'); 
Route::get('actions/{id}', [AuthController::class, 'actions'])->name('user.actions'); 
Route::get('activity', [AuthController::class, 'activity'])->name('activity'); 
Route::get('latest', [AuthController::class, 'latest'])->name('user.latest'); 
Route::get('vote', [AuthController::class, 'vote'])->name('user.vote'); 
Route::get('api/v1/getMatchs', [AuthController::class, 'getMatchs'])->name('getMatchs'); 




