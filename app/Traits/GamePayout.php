<?php

namespace App\Traits;

trait GamePayout
{
    public function calculateBasicPayout($bet_type, $bet_price){

        $bankCommission = 5;
        
        if($bet_type === 'banker')// 1 is banker win
        {
            // take out 5% of Winning amount.
            $commissionAmount = ($bankCommission / 100) * $bet_price;
            $payoutAmount = ($bet_price * 2) - $commissionAmount;
            return $payoutAmount;

        }elseif($bet_type === 'player')// 2 is player win
        {
            $payoutAmount = $bet_price * 2;
            return $payoutAmount;

        }elseif($bet_type === 'tie')// 3 is tie
        {
            $payoutAmount = $bet_price * 8;
            return $payoutAmount;
        }
       
   }


   public function calculatePairPayout($pairStat, $bet_price){
    
        if($pairStat === '1') // Banker pair
        {
            $payoutAmount = $bet_price * 11;
            return $payoutAmount;

        }elseif($pairStat === '2') // Player pair
        {
            $payoutAmount = $bet_price * 11;
            return $payoutAmount;

        }elseif($pairStat === 3) // Both pair
        {
            $payoutAmount = $bet_price * 5;
            return $payoutAmount;
        }

   }



}