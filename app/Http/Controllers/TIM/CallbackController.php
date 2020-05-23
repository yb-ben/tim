<?php


namespace App\Http\Controllers\TIM;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{


    public function callback(Request $request){

        $data = $request->post();
        Log::debug('callback data',$data);
        return response()->json(['ActionStatus'=>'OK','ErrorInfo'=>'','ErrorCode'=>0]);
    }
}
