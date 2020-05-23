<?php


namespace App\Http\Controllers\TIM;


use App\Http\Controllers\Controller;
use App\Utils\TLSSigAPIv2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MsgController extends Controller
{
    protected $sdkappid ;

    protected $key ;

    protected $baseUrl = 'https://console.tim.qq.com/';

    protected $signer ;

    protected $admin = 'admin1';

    protected $userSig ;

    public function __construct()
    {
        $this->sdkappid = env('TIM_SDKAPPID');
        $this->key = env('TIM_APPSECRET');
        $this->signer = new TLSSigAPIv2($this->sdkappid,$this->key);
        $this->userSig = $this->signer->genSig($this->admin);
    }

    public function getUserSig(){
        return $this->userSig;
    }

    protected function getInterface($interface,$query = []){
        $query = array_merge($query,[
            'sdkappid'=> $this->sdkappid,
            'identifier' => $this->admin,
            'usersig' => $this->userSig,
            'random' => random_int(0,4294967295),
            'contenttype' => 'json'
        ]);
        $queryString =  http_build_query($query);
        return $this->baseUrl . $interface.'?'.$queryString;
    }

    protected function post($url,$params){
        return Http::asJson()->post($url,$params)->throw()->json();
    }

    public function sendMsg(){

        $url = $this->getInterface('v4/openim/sendmsg');

        return Http::asJson()->post($url,[
            'To_Account' => 'user1',
            'SyncOtherMachine'=>2,
            'MsgLifeTime'=>60,
            'MsgRandom' => random_int(0,4294967295),
            'MsgTimeStamp'=>time(),
            'MsgBody'=>[
                [
                    'MsgType' => 'TIMTextElem',
                    'MsgContent'=>[
                        'Text'=>'hello,world'
                    ]
                ]
            ]
        ])->throw()->json();
    }


    public function accountImport(){

        $url = $this->getInterface('v4/im_open_login_svc/account_import');
        return Http::asJson()->post($url,[
            "Identifier" => "nocontent",
            "Nick" => "nocontent204",
        ])->throw()->json();
    }

    public function createGroup(){
        $url = $this->getInterface('v4/group_open_http_svc/create_group');
        return $this->post($url, [
            'Owner_Account'=>'admin1',//群主id
            'Type'=>'Public',//群组类型
            'Name'=>'TestGroup', //群名称
            'Introduction'=>'无',//群简介
            'Notification'=>'无',//群公告
            //'FaceUrl'=>'',//群头像
            'MaxMemberCount'=>500,//最大群成员
            'ApplyJoinOption'=>'FreeAccess',//申请加群方式
            'AppDefinedData'=>[
                [
                    'Key'=>'fffffc',
                    'Value'=>'20200523',
                ]
            ],
            'MemberList'=>[
                [
                    'Member_Account'=>'user1',
                    'Role'=> 'Admin',
                    'AppMemberDefinedData'=>[
                        [
                            'Key'=>'ssss',
                            'Value'=>'20200523'
                        ]
                    ]
                ],
            ]
        ]);
    }

    public function command(Request $request,$command){

        return $this->$command();

    }
}
