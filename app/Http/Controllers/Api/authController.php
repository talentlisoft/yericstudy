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
            $credentials = request(['email', 'password']);
            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->successresponse([
                'api_token' => $token,
                'token_type' => 'bearer',
                'name' => auth('api')->user()->name,
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);

            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('api\authController->login->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('api\authController->login->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
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
            $userRecord = User::where('wechart_openid', $wechatData['openid'])->first();
            if ($userRecord) {
                $token = auth('api')->login($userRecord);
                return $this->successresponse(['name' => $userRecord->name, 'api_token' =>$token, 'token_type' => 'bearer', 'expires_in' => auth('api')->factory()->getTTL() * 60]);
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
