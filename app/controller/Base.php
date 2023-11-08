<?php

namespace app\controller;

use app\BaseController;
use think\facade\Session;
use think\facade\Request;

class Base extends BaseController
{
    // 初始化：未登录的用户不允许进入
    protected function initialize()
    {
        $admin = Session::get('admin');
        if (!$admin) {
            if (Request::isAjax()) {
                exit(json_encode(['code' => 1, 'msg' => '你还未登录，请登录']));
            }
            exit('你还未登录，请登录
            <script>
                setTimeout(function(){window.parent.location.href="/account"},2000)
            </script>
            ');
        }
    }
}