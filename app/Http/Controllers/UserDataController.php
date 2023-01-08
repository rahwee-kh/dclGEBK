<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\UserData;
use App\Traits\ApiResponser;
use App\Traits\GamePayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserDataController extends Controller
{
    use ApiResponser;
    use GamePayout;

    public function index(){

        $all_bets = Bet::all();
        return $this->showAll($all_bets, 200);

    }

    public function getUserData(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'user_name'=>'string'
        ]);

        //return response()->json([$request->get('eth_address'),$request->get('name')], 422);       
        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }
        
        $name = $request->get('user_name');
        // Check eth_address
        $user = UserData::where('eth_address', $request->get('eth_address'))->first();

        // Check if user have in database
        if ($user == null) {
            $role = "guest";
            if(strtolower($request->get('eth_address')) == '0x03111dc8c943f367c028968e47a80d3fbdf6e86d') $role = "admin";
            //return response()->json([$request->get('eth_address'),$request->get('name')], 422);      
            $user = UserData::create([
                'eth_address' => $request->get('eth_address'),
                'name' => $name,
                'balance' => 0,
                'role' => $role,
                'payment' => 0.0,
                'bonus' => 0.0,
                'vote' => 0,
            ]);
            return response()->json($user, 201);
        }else{
            //update user name
            $user->name = $name;
            $user->save();
        }

        // Result object
        return response()->json($user, 201);
    }
}
