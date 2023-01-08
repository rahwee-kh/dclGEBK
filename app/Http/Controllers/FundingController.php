<?php

namespace App\Http\Controllers;

use App\Models\CashInRecord;
use App\Models\CashOutRecord;
use App\Models\Bet;
use App\Models\UserData;
use App\Traits\ApiResponser;
use App\Traits\GamePayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FundingController extends Controller
{
    use ApiResponser;
    use GamePayout;

    public function index(){

        $all_bets = Bet::all();
        return $this->showAll($all_bets, 200);

    }

    public function cashIn(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'transaction_hash' => 'required',
            'chips' => 'required'
        ]);

        //TODO: verify transaction hash

        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();
        
        // Check if user have in database
        if(!$user||! $request->chips){
            return response([
                'message' => 'Not found!'
            ], 401);
        }

        $record = CashInRecord::create([
            'eth_address' => $request->get('eth_address'),
            'transaction_hash' => $request->get('transaction_hash'),
            'balance' => $request->get('chips')
        ]);

        $user->balance = $user->balance + $request->chips;
        $user->save();

        // Result object
        return response()->json($user, 201);
    }

    public function cashOut(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'chips' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();

        // Check if user have in database
        if(!$user){
            return response([
                'message' => 'Not found account!'
            ], 401);
        }

        $record = CashOutRecord::create([
            'eth_address' => $request->get('eth_address'),
            'balance' => $request->get('chips'),
            'status' => '0',
            'record_id' => uniqid(),
            'approve_at' => '0'
        ]);

        if($user->balance - $request->chips < 0)
        {
            return response([
                'message' => 'Error Op!'
            ], 401);
        }

        $user->balance = $user->balance - $request->chips;
        $user->save();

        // Result object
        return response()->json($user, 201);
    }

    public function fundingRecords(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();

        // Check if user have in database
        if(!$user){
            return response([
                'message' => 'Not found account!'
            ], 401);
        }

        // Check records
        $records = CashOutRecord::where('eth_address', $request->eth_address)->orderBy('id')->cursorPaginate(10000);

        // Result object
        return response()->json($records, 201);
    }
}
