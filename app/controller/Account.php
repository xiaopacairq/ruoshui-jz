<?php

namespace app\controller;

use app\BaseController;
use think\captcha\facade\Captcha;
use think\facade\Db;
use think\facade\Session;
use think\facade\Request;

class Account extends BaseController
{
    // 登录界面
    public function index()
    {
        return view('/account/login');
    }

    // 注册验证码
    public function verify()
    {
        return Captcha::create();
    }

    // 登录校验
    public function do_login()
    {
        $uname = Request::post('uname', '');
        $pwd = Request::post('pwd', '');
        $captcha = Request::post('captcha', '');

        // 非空验证
        if (!$uname) exit(json_encode(['code' => 1, 'msg' => '用户名不能为空'], true));
        if (!$pwd) exit(json_encode(['code' => 1, 'msg' => '密码不能为空'], true));
        if (!$captcha) exit(json_encode(['code' => 1, 'msg' => '验证码不能为空'], true));

        if (!captcha_check($captcha)) exit(json_encode(['code' => 1, 'msg' => '验证码错误'], true));

        // 用户名是否存在
        $user = Db::table('jz_admin')->where('uname', $uname)->find();
        if (!$user) exit(json_encode(['code' => 1, 'msg' => '用户名错误'], true));

        // 密码校验
        if ($user['pwd'] != $pwd) exit(json_encode(['code' => 1, 'msg' => '密码错误'], true));

        // 通过登录
        $data['last_time'] = date('Y-m-d H:i:s', time());
        $res = Db::table('jz_admin')->where('uname', $user['uname'])->update($data); // 修改登录时间

        Session::set('admin', $user); //将登录信息保存到session中
        Session::delete('captcha'); //删除验证码session

        // exit会导致session失效
        echo json_encode(['code' => 0, 'msg' => '登录成功'], true);
    }
}