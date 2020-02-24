<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Hash;
use App\User;

class authController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'=> 'required | email',
            'password' => 'required'
        ]);
        try {
            $userRecord = DB::table('users')->where('email', $request->input('email'))->first();
            if ($userRecord) {
                if (Hash::check($request->input('password'), $userRecord->password)) {
                    if ($request->has('openid')) {
                        //Bind wechat openid
                        DB::table('users')->where('id', $userRecord->id)->update(['wechart_openid' => $request->input('openid')]);
                    }
                    return $this->successresponse(['name' => $userRecord->name, 'api_token' =>$userRecord->api_token]);
                }
            } elseif ($request->has('openid')) {
                // Create new account from 小程序
                $newuserRecord = new User;
                $newuserRecord->api_token = str_random(64);
                $newuserRecord->name = $request->input('email');
                $newuserRecord->email = $request->input('email');
                $newuserRecord->password = Hash::make($request->input('password'));
                $newuserRecord->permissions = 0;
                if ($newuserRecord->save()) {
                    return $this->successresponse(['name' => $newuserRecord->email, 'api_token' =>$newuserRecord->api_token]);
                }
            }
            return $this->failureresponse('Email address and password mismatch!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('api\authController->login->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('api\authController->login->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function wechartlogin(Request $request)
    {
        $this->validate($request, [
            'js_code' => 'required'
        ]);

        $queryData = [
            'appid' => env('WECHAT_APPID', null),
            'secret' => env('WECHAT_APPSECRET', null),
            'js_code' => $request->input('js_code'),
            'grant_type' => 'authorization_code'
        ];

        $returnstr =  $this->httpget('https://api.weixin.qq.com/sns/jscode2session?' . http_build_query($queryData));
        if ($returnstr) {
            $matcharr = [];
            preg_match_all('/{.+}/', $returnstr, $matcharr);
            $wechatData = json_decode($matcharr[0][0], true);
            $userRecord = DB::table('users')->where('wechart_openid', $wechatData['openid'])->first();
            if ($userRecord) {
                return $this->successresponse(['name' => $userRecord->name, 'api_token' =>$userRecord->api_token]);
            } else {
                return $this->failureresponse($wechatData['openid']);
            }

        } else {
            return $this->failureresponse('微信接口调用失败: 0');
        }
    }

    public function httpget($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
}
