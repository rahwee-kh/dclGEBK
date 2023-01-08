<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string'
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = User::where('eth_address', $request->eth_address)->first();

        // Check password
        if(!$user){
            return response([
                'message' => 'Incorrect credential!'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'name' => $user->name,
                'token' => $token,
                'token_type' => 'Bearer', 
                ], 200);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()
                ->json([
                'message'=>'Successful logout!'
                ], 200);
    }
}
