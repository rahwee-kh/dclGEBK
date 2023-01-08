<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

use App\Models\Event;
use App\Models\TableFive;
use App\Models\TableSix;
use App\Models\TableThirtyTwo;
use App\Traits\ApiResponser;
use App\Traits\DecryptData;
use App\Traits\HandleDataRecord;
use Illuminate\Http\Request;



/**
 * Handle on getting request from
 * api provider and decrypt data
 * save to database
 */

class EventController extends Controller
{
    use DecryptData;
    use ApiResponser;
    
    public function handleEvent(Request $request){

        $rules = [
            'x' => 'required',
            'tableID' => 'required',
            'x1' => 'required',
            'domain' => 'required'
        ];

        
        $this->validate($request, $rules);
        $data = $request->input(); 
        //$data =  file_get_contents('php://input');

        $data['x'] = $this->decrypteDes($request->input('x'));
        $data['tableID'] = $request->input('tableID');
        $data['x1'] = $this->decrypteDes($request->input('x1'));
        $data['domain'] = $request->input('domain');


        if($data['tableID'] === '5'){

           
            $data_created = TableFive::create($data);
             return $this->successResponse($data_created, 201);
        }

        if($data['tableID'] === '6'){
            $data_created = TableSix::create($data);
             return $this->successResponse($data_created, 201);
        
        }

        if($data['tableID'] === '32'){
            $data_created = TableThirtyTwo::create($data);
            return $this->successResponse($data_created, 201);
        }

        return $this->errorResponse("Invalid table ID!", 404);


        // $event = Event::create($data);
        // return response()->json(['data', $event], 201);

        // return response()->json(['data', $data], 201);
           
        // if($data){
        //     $file = time().'_file.text';
        //     $destinationPath=public_path().'/upload/';
        //     if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            
        //     File::put($destinationPath.$file,$data);
        //     return response()->download($destinationPath.$file);
        // }
        // return $data;

    }

    

}
