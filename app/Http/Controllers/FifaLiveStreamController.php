<?php

namespace App\Http\Controllers;

use App\Models\FifaLiveStreamRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Traits\GamePayout;
use Illuminate\Support\Facades\DB;

class FifaLiveStreamController extends Controller
{
    use ApiResponser;
    use GamePayout;

    public function fifaGetStream(){

        $data = DB::table('fifa_live_stream_records')
                ->orderBy('id', 'desc')
                ->first();
        return $this->successResponse($data , 201);

    }



    public function fifaStoreStream(Request $request){

        $validator = Validator::make($request->all(),[
            'video_link' => 'required',
            'status'=>'required',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 422);       
        }

       
        $videoStream = FifaLiveStreamRecord::create([

            'video_link' => $request->get('video_link'),
            'status' => $request->get('status'),
            
        ]);
    

        return $this->successResponse($videoStream, 201);
    }
}
