<?php

namespace App\Http\Controllers;

use App\Models\BetSoccerRecord;
use App\Models\BetFifaRecord;
use App\Models\UserData;
use App\Models\FifaMatch;
use App\Traits\ApiResponser;
use App\Traits\GamePayout;
use App\Traits\FifaResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BetSoccerController extends Controller
{
    use ApiResponser;
    use GamePayout;
    use FifaResult;

    public function index(){

        $all_bets = Bet::all();
        return $this->showAll($all_bets, 200);

    }

    //depecated
    public function storeBetting(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'match_id'=>'required',
            'selection'=>'required',
            'bet_type'=>'required',
            'chips'=>'required'
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }


        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();

        return $this->successResponse($user, 201);
    }

    public function storeBetting2(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'match_id'=>'required',
            'selection'=>'required',
            'bet_type'=>'required',
            'chips'=>'required',
            'commence_time'=>'required',
            'price'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        $currentDate = date(DATE_ATOM);

        $matchs = $this->checkFifaMatchs();
        $match = FifaMatch::where('match_id', '=', $request->match_id)->first();
        if($match == null){
            $match = $this->checkFifaMatch($request->match_id);
            if($match == null) return response(['Not Found Match!' ], 401);
        }


        if(strtotime($currentDate) > strtotime($match->commence_time))
        {
            return response(['Time Out!' ], 401);
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();


        // Check if user have in database
        if($user==null){
            return response([
                'message' => 'Incorrect credential!'
            ], 401);
        }

        if($user->balance - $request->chips < 0)
        {
            return response([
                'message' => 'Error Op!'
            ], 401);
        }

        $user->balance = $user->balance - $request->chips;
        $user->payment = $user->payment + $request->chips;
        $user->save();

        try {
            $bet = BetFifaRecord::create([
                'eth_address' => $request->get('eth_address'),
                'match_id' => $request->get('match_id'),
                'selection' => $request->get('selection'),
                'bet_type' => $request->get('bet_type'),
                'chips' => $request->get('chips'),
                'commence_time' => $request->get('commence_time'),
                'price' => $request->get('price'),
                'result' => "-1"
            ]);
        } catch (\Throwable $th) {
            return $this->successResponse(["create error",$th], 201);
        }


        return $this->successResponse($user, 201);
    }

    public function vote(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'select_team'=>'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();


        // Check if user have in database
        if($user==null){
            return response([
                'message' => 'Incorrect credential!'
            ], 401);
        }

        if($user->vote > 0){
            return $this->successResponse($user, 201);
        }

        $user->vote = $request->select_team;
        $user->save();

        return $this->successResponse($user, 201);
    }


    public function myBets(Request $request){

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
                'message' => 'Incorrect credential!'
            ], 401);
        }

        $bets = BetFifaRecord::where('eth_address', $request->eth_address)->orderBy('id')->cursorPaginate(10000);
        return $this->successResponse($bets, 201);
    }

    public function checkBet(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'match_id'=>'required'
        ]);



        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();

        // Check if user have in database
        if(!$user){
            return response([
                'message' => 'Incorrect credential!'
            ], 401);
        }

        $currentDate = date(DATE_ATOM);

        $matchs = $this->checkFifaMatchs();
        $match = FifaMatch::where('match_id', '=', $request->match_id)->first();

        //return response($match, 401);
        if($match->completed == "0"){
            //check and calc result
            $datas =  $this->getDatas();
            foreach ($datas as $data) {
                if( $data['id'] != $match->match_id)continue;
                $match->completed = "1";

                //set first
                if($data['scores'][0]["name"] === $data['home_team']) $match->home_score = $data['scores'][0]["score"];
                else if($data['scores'][0]["name"] === $data['away_team'])  $match->away_score = $data['scores'][0]["score"];
                //set second
                if($data['scores'][1]["name"] === $data['home_team']) $match->home_score = $data['scores'][1]["score"];
                else if($data['scores'][1]["name"] === $data['away_team'])  $match->away_score = $data['scores'][1]["score"];

                $home_score = (float)$match->home_score;
                $away_score = (float)$match->away_score;
                if($home_score > $away_score)$match->result = "0";
                else if($home_score < $away_score)$match->result = "2";
                else $match->result = "1";
                $match->save();
                break;
            }
        }

        if(strtotime($currentDate) > strtotime($match->commence_time) && $match->completed != "0")
        {
            $bets = BetFifaRecord::where('eth_address', $request->eth_address)->orderBy('id')->cursorPaginate(10000);
            foreach ($bets as $bet) {
                if($bet->match_id !=  $match->match_id)continue;
                if($bet->result != "-1")continue;
                if($match->result == "-1")continue;
                if($bet->selection == $match->result){
                    //win
                    $user->balance = $user->balance + (float)($bet->price)*(float)($bet->chips);
                    $user->bonus = $user->bonus + (float)($bet->price)*(float)($bet->chips);
                }else{
                    //lose
                }
                $bet->result = $match->result;
                $bet->save();
            }
        }
        $user->save();


        return $this->successResponse($user, 201);
    }

    public function ranking(Request $request){

        $validator = Validator::make($request->all(),[
            'eth_address' => 'required|string',
            'ranking_type'=>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

        // Check eth_address
        $user = UserData::where('eth_address', $request->eth_address)->first();

        // Check if user have in database
        if(!$user){
            return response([
                'message' => 'Incorrect credential!'
            ], 401);
        }

        $users = UserData::where('role', '!=', 'cheater')->orderBy('payment','DESC')->cursorPaginate(50);
        //$users = UserData::where('balance', '>=', 0)->orderBy('balance','DESC')->cursorPaginate(50);

        $records = [];
        $index = 1;
        foreach ($users as $user) {
            $dic['index']=$index++;
            $dic['name']=$user->name?$user->name:"anonymous";
            $dic['payment']=$user->payment;
            $dic['bonus']=$user->bonus;
            $dic['vote']=$user->vote;
            array_push($records,$dic);
        }

        return $this->successResponse($records, 201);
    }
}
