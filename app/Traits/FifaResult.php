<?php

namespace App\Traits;
use App\Models\FifaMatch;

trait FifaResult
{
    public function getDatas(){
        $url = "https://api.the-odds-api.com/v4/sports/soccer_fifa_world_cup/scores/?daysFrom=3&apiKey=b2dd02554ff1f173429d8d93f32c7617";
        $datas = json_decode(file_get_contents($url), true);
        return $datas;
    }

    public function checkFifaMatchs(){

        $currentDate = date(DATE_ATOM);
        $matchs = FifaMatch::all();
        if(count($matchs)<=0){
            $url = "https://api.the-odds-api.com/v4/sports/soccer_fifa_world_cup/scores/?daysFrom=3&apiKey=b2dd02554ff1f173429d8d93f32c7617";
            $datas = json_decode(file_get_contents($url), true);
            foreach ($datas as $data) {
                $isCreated = false;
                foreach ($matchs as $match) {
                    if($match->match_id == $data['id'])
                    {
                        $isCreated = true; 
                        break;
                    }
                }
                if($isCreated)continue;
                $match = FifaMatch::create([
                    'match_id' => $data['id'],
                    'sport_key' => $data['sport_key'],
                    'sport_title' => $data['sport_title'],
                    'commence_time' => $data['commence_time'],
                    'completed' => $data['completed'],
                    'home_team' => $data['home_team'],
                    'away_team' => $data['away_team'],
                    'home_score' => "0",
                    'away_score' => "0",
                    'result' => "-1",
                    'last_update' => $currentDate
                ]);
            }
            $matchs = FifaMatch::all();
        }
        return $matchs;
   }

   public function checkFifaMatch($match_id){

    $currentDate = date(DATE_ATOM);
    $matchs = FifaMatch::all();

    $url = "https://api.the-odds-api.com/v4/sports/soccer_fifa_world_cup/scores/?daysFrom=3&apiKey=b2dd02554ff1f173429d8d93f32c7617";
    $datas = json_decode(file_get_contents($url), true);
    foreach ($datas as $data) {
        $isCreated = false;
        foreach ($matchs as $match) {
            if($match->match_id == $data['id'])
            {
                $isCreated = true; 
                break;
            }
        }
        if($isCreated)continue;
        $match = FifaMatch::create([
            'match_id' => $data['id'],
            'sport_key' => $data['sport_key'],
            'sport_title' => $data['sport_title'],
            'commence_time' => $data['commence_time'],
            'completed' => $data['completed'],
            'home_team' => $data['home_team'],
            'away_team' => $data['away_team'],
            'home_score' => "0",
            'away_score' => "0",
            'result' => "-1",
            'last_update' => $currentDate
        ]);
    }
    $matchs = FifaMatch::all();

    foreach ($matchs as $match) {
        if($match->match_id == $match_id) return $match;
    }

    return null;
}
}