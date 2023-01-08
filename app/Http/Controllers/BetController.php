<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\UserData;
use App\Traits\ApiResponser;
use App\Traits\GamePayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BetController extends Controller
{
    use ApiResponser;
    use GamePayout;

    public function index(){

        $all_bets = Bet::all();
        return $this->showAll($all_bets, 200);

    }

    public function storeBetting(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'bet_type'=>'required',
            'bet_price'=>'required',
            'bet_round'=>'required',
            'bet_shoe'=>'required',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }


        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();


        // Check if user have in database
        if($user == null){
            return response([
                'message' => 'Incorrect credential!'
            ], 401);
        }

        $bet = Bet::create([
            // 'user_id' => $user->id,
            'user_name' => $request->get('name'),
            'bet_type' => $request->get('bet_type'),
            'bet_price' => $request->get('bet_price'),
            'est_price' => $this->calculateBasicPayout($request->get('bet_type'), (int)$request->get('bet_price')),
            //'est_pair_price' => $this->calculatePairPayout($request->get('pair_state'), (int)$request->get('bet_price')),
            'bet_round' => $request->get('bet_round'),
            'bet_shoe' => $request->get('bet_shoe')
        ]);

        return $this->successResponse($bet, 201);
    }
}
