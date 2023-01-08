<?php

namespace App\Http\Controllers;


use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\GamePayout;
use App\Traits\HandleDataRecord;
use Illuminate\Support\Facades\Validator;

/**
 * Handle on getting request form client
 * and response back the result
 * of by tableID
 */

class GameResultController extends Controller
{
    // Use Trait GamePayout
    use GamePayout;
    use ApiResponser;
    use HandleDataRecord;


    /**
     * Handle returning last record
     */
    public function gameResultListByTableID(Request $request){

        $validator = Validator::make($request->all(),[
            'tableID' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $request_field = $request->input();
        $tableid = $request_field['tableID'];

        $latestRecord = $this->handleTableIDBase($tableid);

        // All winner
        $winners = $this->handleWinner($latestRecord);

        // Result object
        $result = $this->successResponse($latestRecord, 200);
    
        return [$result, $winners];
        

    }

    private function handleRoundShoe(){
        $datas = DB::table('table_fives')
        ->get();
        return $datas;
    }

    /**
    * Handle bet price from user
    */
    private function handleWinner($latestRecord) {
        
        if($latestRecord && $latestRecord->x1 === "GP_WINNER"){

            $x_value = json_decode($latestRecord->x, true);
            $x_game_round = $x_value["gameRound"];
            $x_game_shoe = $x_value["gameShoe"];
            

            if($x_value["winner"] === 1) {//1 is banker
                
                $bets = DB::table('bets')
                ->where('bet_type', '=', 'banker')
                ->where('bet_round', '=', $x_game_round)
                ->where('bet_shoe', '=', $x_game_shoe)
                ->get();

                return $bets;

                
            }

            if($x_value["winner"] === 2) { // 2 is player

                $bets = DB::table('bets')
                ->where('bet_type', '=', 'player')
                ->where('bet_round', '=', $x_game_round)
                ->where('bet_shoe', '=', $x_game_shoe)
                ->get();

                return $bets;
            }

            if($x_value["winner"] === 3) { // 3 is tie
                
                $bets = DB::table('bets')
                ->where('bet_type', '=', 'tie')
                ->where('bet_round', '=', $x_game_round)
                ->where('bet_shoe', '=', $x_game_shoe)
                ->get();

                return $bets;
            }

        }
    }



}
