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

        if(!empty($data) && isset($data['CallbackCommand'])){
            $count = 1;
            $method = str_replace('C2C.Callback','',$data['CallbackCommand'],$count);
            $method = lcfirst($method);
            return $this->$method($request);
        }
        return response()->json(['ActionStatus'=>'OK','ErrorInfo'=>'','ErrorCode'=>0]);
    }


    public function beforeSendMsg(Request $request){
        //$data = $request->post();
        return response()->json([
            'ActionStatus'=>'OK','ErrorInfo'=>'','ErrorCode'=>0,
            'MsgBody'=>[
                [
                    'MsgType'=>'TIMTextElem',
                    'MsgContent'=>[
                        'Text'=>'red packet'
                    ]
                ]
            ]
        ]);
    }
}
