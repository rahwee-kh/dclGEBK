<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\UserData;
use App\Models\CashOutRecord;
use App\Models\FifaMatch;
use App\Traits\FifaResult;
use App\Models\BetFifaRecord;
use App\Models\CashInRecord;
use Hash;

use Elliptic\EC;
use Illuminate\Support\Str;
use kornrunner\Keccak;



class AuthController extends Controller
{
    use FifaResult;
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        if(Auth::check()){
            return redirect('account');
        }
        return view('login');
    }  
      

    public function message()
    {
        $nonce = Str::random();
        $message = "Sign this message to confirm you own this wallet address. This action will not cost any gas fees.\n\nNonce: " . $nonce;

        session()->put('sign_message', $message);
        
        return $message;
    }

    public function verify(Request $request)
    {
        $result = $this->verifySignature(
            session()->pull('sign_message'),
            $request->input('signature'),
            $request->input('address')

            );
            
        $userData = UserData::where('eth_address', '=', $request->input('address'))->first();
        if ($userData === null) {
            $role = "guest";
            if(strtolower($request->input('address')) == '0x03111dc8c943f367c028968e47a80d3fbdf6e86d') $role  = "admin";
            $userData = UserData::create([
                'eth_address' => $request->input('address'),
                'balance' => 0,
                'role' => $role,
                'payment' => 0,
                'bonus' => 0,
                'vote' => 0,
            ]);
        }else{
            if(strtolower($request->input('address')) == '0x03111dc8c943f367c028968e47a80d3fbdf6e86d') 
            {    
                $userData->role = "admin";
                $userData->save();
            }
        }
        $user = User::where('eth_address', '=', $request->input('address'))->first();
        if ($user === null) {
            $user = User::create([
                'eth_address' => $request->input('address'),
                'balance' => 0
            ]);
                Auth::login($user);
        }else{
                Auth::login($user);
        }

    }


    public function approve(Request $request){
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }
        }else{
            return Redirect('account');
        }

        $id = $request->id;
        $record = CashOutRecord::where('id', '=', $id)->first();
        $record->status =  $record->status == '1'?'0':'1';
        $record->save();

        return Redirect('records');
    }
    
    public function upgrade(Request $request){
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }
        }else{
            return Redirect('account');
        }

        $eth_address = $request->id;
        if(strtolower($eth_address) != '0x03111dc8c943f367c028968e47a80d3fbdf6e86d') 
        {
            $user = UserData::where('eth_address', '=', $eth_address)->first();
            if ($user === null) {
                return Redirect('users');
            }
            if($user->role == 'admin')$user->role = 'guest';
            else if($user->role == 'guest')$user->role = 'admin';
            $user->save();
        }

        return Redirect('users');
    }

    public function block(Request $request){
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }
        }else{
            return Redirect('account');
        }

        $eth_address = $request->id;
        if(strtolower($eth_address) != '0x03111dc8c943f367c028968e47a80d3fbdf6e86d') 
        {
            $user = UserData::where('eth_address', '=', $eth_address)->first();
            if ($user === null) {
                return Redirect('users');
            }
            if($user->role == 'cheater')$user->role = 'guest';
            else $user->role = 'cheater';
            $user->save();
        }

        return Redirect('users');
    }
    
    public function redeem4(Request $request){
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }
        }else{
            return Redirect('account');
        }

         $eth_address = $request->eth_address;
         $chips = $request->chips;
         if($eth_address == 'all'){
            $users = UserData::all();
            foreach ($users as $user) {
                $user->balance += $chips;
                $user->save();
            }
         }else if(str_contains($eth_address,',')){
            $walletArr = explode(',', $eth_address);
            foreach ($walletArr as $wallet) { 
                $user = UserData::where('eth_address', '=', $wallet)->first();
                if ($user === null) continue;
                $user->balance += $chips;
                $user->save();
            }
         }else{
             $user = UserData::where('eth_address', '=', $eth_address)->first();
             if ($user === null) {
                 return Redirect('users');
             }
            $user->balance += $chips;
            $user->save();
        }

        return Redirect('users');
    }

    public function redeemVip(Request $request){
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }
        }else{
            return Redirect('account');
        }

         $chips = $request->chips;
         $walletStr = $request->wallets;
         $wallets = explode( ",", $walletStr);
         $existWallets = [];

         $users = UserData::all();
         foreach ($users as $user) {
            if($user->role == 'vip')
            {
                array_push($existWallets, $user->eth_address);
                continue;
            }
            foreach($wallets as $wallet) {    
                if($wallet == $user->eth_address){
                    $user->balance += $chips;
                    $user->role = "vip";
                    $user->save();
                    array_push($existWallets, $user->eth_address);
                    break;
                }
            }
         }

         foreach($wallets as $wallet) {   
            if($wallet == null || $wallet == '')continue;
            if (in_array($wallet, $existWallets)==false){
                $userData = UserData::create([
                    'eth_address' => $wallet,
                    'balance' => $chips,
                    'role' => 'vip',
                    'payment' => 0,
                    'bonus' => 0,
                    'vote' => 0,
                ]);
            }
        }

        return Redirect('users');
    }

    protected function verifySignature(string $message, string $signature, string $address)
    {
        $hash = Keccak::hash(sprintf("\x19Ethereum Signed Message:\n%s%s", strlen($message), $message), 256);
        $sign = [
            'r' => substr($signature, 2, 64),
            's' => substr($signature, 66, 64),
        ];
        $recid = ord(hex2bin(substr($signature, 130, 2))) - 27;

        if ($recid != ($recid & 1)) {
            return false;
        }

        $pubkey = (new EC('secp256k1'))->recoverPubKey($hash, $sign, $recid);
        $derived_address = '0x' . substr(Keccak::hash(substr(hex2bin($pubkey->encode('hex')), 1), 256), 24);

        return (Str::lower($address) === $derived_address);
    }



    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {

        if(Auth::check()){
            
            return view('dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function records()
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }


            $records = CashOutRecord::all();
            foreach ($records as $record) {
                if($record->eth_address == null) $record->eth_address = "";
            }
            return view ('records')->with('records', $records);
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    
    public function users()
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }
            //echo '1';
            //$users = UserData::whereNotNull('eth_address')->get();
            $users = UserData::all();
            foreach ($users as $user) {
                if($user->eth_address == null) $user->eth_address = "";
            }
            return view ('users')->with('users', $users);
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function activity()
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }

            $users = UserData::where('balance', '>=', 0)->orderBy('updated_at','DESC')->cursorPaginate(99999);
            foreach ($users as $user) {
                if($user->eth_address == null) $user->eth_address = "";
            }
            return view ('activity')->with('users', $users);
        }
        
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function latest(Request $request)
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }

            $records = [];
            //bet
            $betRecords = BetFifaRecord::where('eth_address', '!=', "")->orderBy('created_at','DESC')->cursorPaginate(99999);
            foreach ($betRecords as $record) {
                $selection = 'x';
                $result = 'unknown';
                $earnChips = 0;
                if($record->selection == '0')$selection = '1';
                else if($record->selection == '1')$selection = 'x';
                else $selection = '2';
                if($record->result == '-1')$result = 'unknown';
                else if($record->result == $record->selection)$result = 'win';
                else$result = 'lose';
                $dic['action']='pay '.$record->chips.' chips to '.$selection.', odd is '.$record->price.', result is '.$result;
                $dic['created_at']=$record->created_at;
                $dic['id']=$record->match_id;
                array_push($records,$dic);
            }

            //cash in
            $cashInRecords = CashInRecord::where('eth_address', '!=', "")->orderBy('created_at','DESC')->cursorPaginate(99999);
            foreach ($cashInRecords as $record) {
                $dic['action']='buy '.$record->balance.' chips';
                $dic['created_at']=$record->created_at;
                $dic['id']=$record->transaction_hash;
                array_push($records,$dic);
            }

            usort($records, function ($item1, $item2) {
                return $item2['created_at'] <=> $item1['created_at'];
            });
            return view ('latest')->with('records', $records);
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function vote(Request $request)
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }

            $records = [];
            //bet
            $users = UserData::where('vote', '!=', 0)->orderBy('vote','DESC')->cursorPaginate(99999);
            foreach ($users as $user) {
                $dic['eth_address']=$user->eth_address;
                $dic['name']=$user->name;
                $dic['team']="";
                if($user->vote == 1) $dic['team']="Argentina";
                if($user->vote == 2) $dic['team']="Croatia";
                if($user->vote == 3) $dic['team']="France";
                if($user->vote == 4) $dic['team']="Morocco";
                //$dic['team']=$user->vote;
                array_push($records,$dic);
            }

            // usort($records, function ($item1, $item2) {
            //     return $item2['created_at'] <=> $item1['created_at'];
            // });
            return view ('vote')->with('records', $records);
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }


    public function actions(Request $request)
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }

            $eth_address = $request->id;
            $records = [];
            $userData = UserData::where('eth_address', '=', $eth_address)->first();

           //regist user
            {
                $dic['action']= 'regist guest';
                $dic['created_at']= $userData->created_at;
                $dic['id']= $eth_address;
                array_push($records,$dic);
            }


            //clear chips
            {
                $dic['action']= 'clear dummy chips, balance is 0';
                $dic['created_at']= '2022-11-21 20:59:00';
                $dic['id']= $eth_address;
                array_push($records,$dic);
            }

             //upgrade vip 
            if($userData->role == 'vip'){
                $dic['action']= 'upgrade vip get 100 chips';
                $dic['created_at']= '2022-11-21 21:00:00';
                $dic['id']= $eth_address;
                array_push($records,$dic);
            }

           //detect vote
           if($userData->vote == 1){
                $dic['action']= 'voting winner get 77 chips';
                $dic['created_at']= '2022-12-20 14:00:00';
                $dic['id']= $eth_address;
                array_push($records,$dic);
            }

            //bet
            $betRecords = BetFifaRecord::where('eth_address', '=', $eth_address)->orderBy('created_at','DESC')->cursorPaginate(99999);
            foreach ($betRecords as $record) {
                $selection = 'x';
                $result = 'unknown';
                $earnChips = 0;
                if($record->selection == '0')$selection = '1';
                else if($record->selection == '1')$selection = 'x';
                else $selection = '2';
                if($record->result == '-1')$result = 'unknown';
                else if($record->result == $record->selection)$result = 'win';
                else$result = 'lose';
                $dic['action']='pay '.$record->chips.' chips to '.$selection.', odd is '.$record->price.', result is '.$result;
                $dic['created_at']=$record->created_at;
                $dic['id']=$record->match_id;
                array_push($records,$dic);
            }

            //cash in
            $cashInRecords = CashInRecord::where('eth_address', '=', $eth_address)->orderBy('created_at','DESC')->cursorPaginate(99999);
            foreach ($cashInRecords as $record) {
                $dic['action']='buy '.$record->balance.' chips';
                $dic['created_at']=$record->created_at;
                $dic['id']=$record->transaction_hash;
                array_push($records,$dic);
            }

            //cash out
            $cashOutRecords = CashOutRecord::where('eth_address', '=', $eth_address)->orderBy('created_at','DESC')->cursorPaginate(99999);
            foreach ($cashOutRecords as $record) {
                $dic['action']='cash out '.$record->balance.' chips';
                $dic['created_at']=$record->created_at;
                $dic['record_id']=$record->transaction_hash;
                array_push($records,$dic);
            }

            usort($records, function ($item1, $item2) {
                return $item1['created_at'] <=> $item2['created_at'];
            });
            return view ('actions')->with('records', $records);
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function matchs()
    {
        if(Auth::check()){
            $userData = UserData::where('eth_address', '=', Auth::user()->eth_address)->first();
            if(!$userData||$userData->role!='admin')
            {    
                return Redirect('account');
            }

            $d = date(DATE_ATOM);
            echo($d);
            $matchs = $this->checkFifaMatchs();

            return view ('matchs')->with('matchs', $matchs);        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function getMatchs()
    {
        $matchs = $this->checkFifaMatchs();

        $records = [];
        foreach ($matchs as $match) {
            $dic['id']= $match->match_id;
            $dic['commence_time']=$match->commence_time;
            $dic['home_team']=$match->home_team;
            $dic['away_team']=$match->away_team;
            array_push($records,$dic);
        }

        usort($records, function ($item1, $item2) {
            return $item1['commence_time'] <=> $item2['commence_time'];
        });

        return $records;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {

        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}
