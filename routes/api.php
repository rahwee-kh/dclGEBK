<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BetController;
use App\Http\Controllers\BetSoccerController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\FundingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FifaLiveStreamController;
use App\Http\Controllers\GameResultController;
use App\Models\FifaLiveStreamRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * gameresult route for receiving result
 * request from api.
 */
Route::post('v1/gameresults',[EventController::class, 'handleEvent']);

/**
 * gameresult route for receiving result
 * request from api.
 */
Route::post('v1/gameresultlist',[GameResultController::class, 'gameResultListByTableID']);

/**
 * TEST LINK
 * gameresult route for receiving result
 * request from api.
 */

Route::get('v1/payoutcalculate/{gametype}',[GameResultController::class, 'payoutCalculate']);

//PUBLIC ROUTES
// Route::post('v1/login', [AuthController::class, 'login']);

// STORE BET
Route::post('v1/placebet', [BetController::class, 'storeBetting']);

// GET User Data
Route::post('v1/getuserdata',  [UserDataController::class, 'getUserData']);

Route::post('v1/cashin',  [FundingController::class, 'cashIn']);

Route::post('v1/cashout',  [FundingController::class, 'cashOut']);

Route::post('v1/soccer',  [BetSoccerController::class, 'storeBetting']);//depecated

Route::post('v1/storeSoccer',  [BetSoccerController::class, 'storeBetting2']);

Route::post('v1/mybets',  [BetSoccerController::class, 'myBets']);

Route::post('v1/ranking',  [BetSoccerController::class, 'ranking']);

Route::post('v1/checkbet',  [BetSoccerController::class, 'checkBet']);

Route::post('v1/fundingrecords',  [FundingController::class, 'fundingRecords']);

Route::post('v1/fifastorestream',  [FifaLiveStreamController::class, 'fifaStoreStream']);

Route::get('v1/fifagetstream',  [FifaLiveStreamController::class, 'fifaGetStream']);

Route::post('v1/vote', [BetSoccerController::class, 'vote']);
//PROTECTED ROUTES
// Route::group(['middleware' => ['auth:sanctum']], function () {

//     // Route::get('v1/user-profile', function() {
//     //     return auth()->user();
//     // });
//     // Route::post('v1/user-logout', [AuthController::class, 'logout']);
// });
// 














