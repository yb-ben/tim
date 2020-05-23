<?php


namespace App\Http\Controllers\TIM;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{

    protected $commands = [
        'C2C.CallbackBeforeSendMsg' => 'beforeSendMsg',
        'Group.CallbackBeforeSendMsg' => 'groupBeforeSendMsg',
    ];

    public function callback(Request $request){

        $data = $request->post();

        Log::debug('callback data',$data);

        if(!empty($data)
            && isset($data['CallbackCommand'])
            && isset($this->commands[$data['CallbackCommand']])
            && !empty($this->commands[$data['CallbackCommand']]))
        {
            return call_user_func([$this,$this->commands[$data['CallbackCommand']]],$request);
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

    public function groupBeforeSendMsg(Request $request){
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
