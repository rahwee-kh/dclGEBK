<?php

namespace App\Traits;
use App\Models\TableFive;
use App\Models\TableSix;
use App\Models\TableThirtyTwo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait HandleDataRecord 
{

    /**
     * handle the every last record 
     */
    public function handleTableIDBase($tableid){
        switch($tableid){
            case $tableid === '5':
                $data = DB::table('table_fives')
                ->orderBy('id', 'desc')
                ->first();
                return $data;
                break;

            case $tableid === '6':
                $data = DB::table('table_sixes')
                ->orderBy('id', 'desc')
                ->first();
                return $data;
                break;

            case $tableid === '32':
                $data = DB::table('table_thirty_twos')
                ->orderBy('id', 'desc')
                ->first();
                return $data;
                break;
                
            default:
                return $this->errorResponse("Invalid table ID!", 404);

        }
        
    }


    public function deleteOldRecord($tableID){

        switch($tableID){
            case $tableID === '5':
                $rows = TableFive::whereDate('created_at', '<=', now()->subDays(1))->delete();
                return $rows;
                break;
            case $tableID === '6':
                $rows = TableSix::whereDate('created_at', '<=', now()->subDays(1))->delete();
                return $rows;
                break;

            case $tableID === '32':
                $rows = TableThirtyTwo::whereDate('created_at', '<=', now()->subDays(1))->delete();
                return $rows;
                break;
            default:
                return $this->errorResponse("Invalid table ID!", 404);

        }

        
    }
}