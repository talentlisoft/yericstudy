<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

        //接口成功返回数据
        public function successresponse($data = null, $total = 0)
        {
            return ['result' => true, 'errorinfo' => '', 'data' => $data, 'total' => $total];
        }
        //接口失败返回数据
        public function failureresponse($errorinfo)
        {
            return ['result' => false, 'errorinfo' => $errorinfo, 'data' => [], 'total' => 0];
        }
}
