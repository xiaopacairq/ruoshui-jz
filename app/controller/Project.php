<?php

namespace app\controller;

use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;

class Project extends Base
{
    // 主页
    public function index()
    {
        // 当前用户
        $admin = Session::get('admin'); //获取当前的用户信息
        $data['uname'] = $admin['uname'];

        // 是否展示天气预报接口，取决于是否请求到数据
        // 0隐藏 1显示
        $data['is_show'] = 1;


        // 获取本机ip信息
        $ip = $this->get_ip(0, true);  //上线后关闭
        // 根据ip地址获取地区信息
        $areas = $this->get_data('http://apis.juhe.cn/ip/ipNew', [
            "ip" => $ip, //需要查询的IP地址或域名
            "key" => '6ddd383a08baf80a1b22e66fb201fcb3', //应用APPKEY(应用详细页查询)
        ]);

        // 如果地区信息不存在或请求失败，则不展示;
        if (!$areas) {
            $data['is_show'] = 0;
            // print_r('获取地区失败');
        } else {
            // print_r('获取地区成功');
            if ($areas['error_code'] > 0) {
                // print_r('获取地区失败');
                $data['is_show'] = 0;
            } else {
                // print_r($areas);
                // 获取完整的地区名
                $area = $areas['result']['City'];

                // 截掉最后一个：区
                $area = substr($area, 0, strlen($area) - 3);

                // 如果地区信息存在的话，请求天气信息
                $weather = $this->get_data('http://apis.juhe.cn/simpleWeather/query', [
                    "city" => $area, //需要查询的城市名
                    "key" => '6bee8f4f11b4f8d6d0bf087ce35cf3e5', //应用APPKEY(应用详细页查询)
                ]);
                // 如果天气数据不存在或请求完了
                if (!$weather) {
                    $data['is_show'] = 0;
                    // print_r('获取天气失败');
                } else {
                    if ($weather['error_code'] > 0) {
                        $data['is_show'] = 0;
                        // print_r('获取天气失败');
                    } else {
                        $data['weather'] = $weather;
                        // print_r('获取天气成功');
                        // print_r($data['weather']);
                    }
                }
            }
        }

        // 生成分表数据
        $data['c_list'] = Db::table('jz_project_c')->order('cid', 'desc')->select()->toArray();

        // 网页统计数据
        $data['cost_count'] = Db::table('jz_cost')->count();  //经费明细总数
        $data['project_total_count'] = Db::table('jz_project_total')->count();  //项目总数

        //记账天数 (开始时间为2023年3月)
        $start_time = strtotime('2022-1-1');
        $end_time = strtotime(date('Y-m-d', time()));
        $data['jz_time'] = abs(round(($end_time - $start_time) / 86400));

        return view('/project/index', $data);
    }

    // 查询登陆地本地ip地址
    private function get_ip($type = 0, $adv = true)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if (null !== $ip) {
            return $ip[$type];
        }

        $httpAgentIp = Config::get('http_agent_ip');

        if ($httpAgentIp && isset($_SERVER[$httpAgentIp])) {
            $ip = $_SERVER[$httpAgentIp];
        } elseif ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip = trim(current($arr));
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip   = $long ? [$ip, $long] : ['0.0.0.0', 0];
        return $ip[$type];
    }

    // 聚合请求数据
    private function get_data($url = '', $params = [])
    {
        $paramstring = http_build_query($params);
        $content = $this->juheHttpRequest($url, $paramstring, 1);
        $result = json_decode($content, true);
        if ($result) {
            if ($result['error_code'] == 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 发起网络请求函数
     * @param $url 请求的URL
     * @param bool $params 请求的参数内容
     * @param int $ispost 是否POST请求
     * @return bool|string 返回内容
     */
    private function juheHttpRequest($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();

        //强制使用 HTTP/1.1
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        //在HTTP请求中包含一个"User-Agent: "头的字符串。
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        // 在尝试连接时等待的秒数。设置为0，则无限等待。
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        //允许 cURL 函数执行的最长秒数。
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        // true 将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 如果传入的参数为1，则开启post请求
        if ($ispost) {
            // true 时会发送 POST 请求
            curl_setopt($ch, CURLOPT_POST, true);
            // 请求参数
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            // 请求地址
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            // 如果不传入参数，则为get请求
            if ($params) {
                // 如果有参数，则用？拼接get
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                // 如果没有参数，直接使用get
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        // 执行curl会话
        $response = curl_exec($ch);

        // 若执行成功，则返回true，错误则返回false
        if ($response === FALSE) {
            // echo "cURL Error: ".curl_error($ch);
            return false;
        }
        //获取一个cURL连接资源句柄的信息
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //合并一个或多个数组
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        // 结束curl请求
        curl_close($ch);
        return $response;
    }


    // 若水学习会经费明细总表
    public function project_cost_list()
    {
        // 当前用户
        $admin = Session::get('admin'); //获取当前的用户信息
        $data['uname'] = $admin['uname'];

        $data['t_money'] = Db::table('jz_cost')->field('u_money')->sum('u_money');
        return view("/project/project_cost_list", $data);
    }

    // 若水学习会经费明细总表:动态渲染表格数据
    public function project_cost_data()
    {
        $where = []; //默认输入框检索条件为空
        $whereTime = [0 => '1999-1-1', 1 => '2999-12-31']; //默认时间范围检索条件为空

        // 检索条件
        $start_time = Request::get('start_time', '');
        $end_time = Request::get('end_time', '');
        $search_remark = Request::get('search_remark', '');
        $search_u_name = Request::get('search_u_name', '');


        if ($start_time && $end_time) {
            $whereTime = array($start_time, $end_time);
        }
        if ($search_remark) {
            $where[] = ['remark', 'like', '%' . $search_remark . '%'];
        }
        if ($search_u_name) {
            $where[] = ['u_name', 'like', '%' . $search_u_name . '%'];
        }
        // print_r(Request::get(''));

        $data['cost'] = Db::table('jz_cost')->where($where)->whereTime('add_time', 'between', $whereTime)->order('id', 'desc')->limit(10000)->select();

        // print_r($data['cost']);
        // dump($data);
        exit(json_encode(['code' => 0, 'msg' => '请求成功', 'data' => $data['cost'], 'search' => Request::get('')], true));
    }

    // 详细信息页面
    public function project_cost_detail()
    {
        $id = request::get('id');
        $data['cost'] = Db::table('jz_cost')->where('id', $id)->find();
        return view("/project/project_cost_detail", $data);
    }

    // 添加数据
    public function project_cost_add()
    {
        return view("/project/project_cost_add");
    }

    // 修改数据
    public function project_cost_edit()
    {
        $id = Request::get('id', '');
        if ($id == '') {
            exit(json_encode(['code' => 1, 'msg' => '修改失败'], true));
        }
        $data['cost'] = Db::table('jz_cost')->where('id', $id)->find();
        return view("/project/project_cost_edit", $data);
    }

    // 删除数据
    public function project_cost_del()
    {
        $id = Request::get('id', '');
        $res = Db::table('jz_cost')->where('id', $id)->delete();
        if (!$res) {
            exit(json_encode(['code' => 1, 'msg' => '删除失败'], true));
        } else {
            exit(json_encode(['code' => 0, 'msg' => '删除成功'], true));
        }
    }

    // 添加数据保存
    public function project_cost_add_save()
    {
        $data['add_time'] = Request::post('add_time', '');
        $data['u_name'] = Request::post('u_name', '');
        $data['u_money'] = Request::post('u_money', '');
        $data['remark'] = Request::post('remark', '');
        if ($data['add_time'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '时间不能为空'], true));
        }
        if ($data['u_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '登记人不能为空'], true));
        }
        if ($data['u_money'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '记账金额不能为空'], true));
        }
        if ($data['remark'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '信息备注不能为空'], true));
        }

        $res = Db::table('jz_cost')->insert($data);
        if ($res) {
            exit(json_encode(['code' => 0, 'msg' => '添加成功'], true));
        } else {
            exit(json_encode(['code' => 1, 'msg' => '添加失败'], true));
        }
    }

    // 修改数据保存
    public function project_cost_edit_save()
    {
        $id = Request::post('id', '');
        $data['add_time'] = Request::post('add_time', '');
        $data['u_name'] = Request::post('u_name', '');
        $data['u_money'] = Request::post('u_money', '');
        $data['remark'] = Request::post('remark', '');
        if ($id == '') {
            exit(json_encode(['code' => 1, 'msg' => '修改失败'], true));
        }
        if ($data['add_time'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '时间不能为空'], true));
        }
        if ($data['u_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '登记人不能为空'], true));
        }
        if ($data['u_money'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '记账金额不能为空'], true));
        }
        if ($data['remark'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '信息备注不能为空'], true));
        }

        $res = Db::table('jz_cost')->where('id', $id)->update($data);
        exit(json_encode(['code' => 0, 'msg' => '修改成功'], true));
    }


    // 项目情况总表
    public function project_total_list()
    {
        // 当前用户
        $admin = Session::get('admin'); //获取当前的用户信息
        $data['uname'] = $admin['uname'];

        $data['project_c_list'] = Db::table('jz_project_c')->order('cid', 'desc')->select()->toArray();

        // 分类表条件
        $cid = Request::get('cid', '');
        if ($cid) $data['project_c_list'] = Db::table('jz_project_c')->where('cid', $cid)->find();
        return view("/project/project_total_list", $data);
    }

    // 项目情况数据
    public function project_total_data()
    {
        $where = []; //默认输入框检索条件为空
        $whereTime = [0 => '1999-1-1', 1 => '2999-12-31']; //默认时间范围检索条件为空

        // 检索条件
        $start_time = Request::get('start_time', '');
        $end_time = Request::get('end_time', '');
        $search_p_name = Request::get('search_p_name', '');
        $search_r_name = Request::get('search_r_name', '');

        // 分类表条件
        $cid = Request::get('cid', '');


        if ($start_time && $end_time) {
            $whereTime = array($start_time, $end_time);
        }
        if ($search_p_name) {
            $where[] = ['p_name', 'like', '%' . $search_p_name . '%'];
        }
        if ($search_r_name) {
            $where[] = ['r_name', 'like', '%' . $search_r_name . '%'];
        }
        if ($cid) {
            $where[] = ['p.cid', '=', $cid];
        }
        // print_r(Request::get(''));
        // print_r($where);
        // print_r($whereTime);
        // exit;

        // 获取数据
        $data['total'] = Db::table('jz_project_total p')
            ->leftJoin('jz_project_c c', 'p.cid=c.cid')
            ->where($where)
            ->whereTime('add_time', 'between', $whereTime)
            ->order('id', 'desc')
            ->select()
            ->toArray();
        // dump($data);

        exit(json_encode(
            [
                'code' => 0,
                'msg' => '请求成功',
                'data' => $data['total'],
                'search' => Request::get('')
            ],
            true
        ));
    }

    // 详细信息页面
    public function project_total_detail()
    {
        $id = request::get('id');
        $data['project_total'] = Db::table('jz_project_total p')->join('jz_project_c c', 'p.cid=c.cid')->where('id', $id)->find();
        return view("/project/project_total_detail", $data);
    }

    // 项目情况添加
    public function project_total_add()
    {
        $data['project_c_list'] = Db::table('jz_project_c')->order('cid', 'desc')->select()->toArray();
        return view("/project/project_total_add", $data);
    }

    // 项目情况修改
    public function project_total_edit()
    {
        $id = Request::get('id', '');
        if ($id == '') {
            exit(json_encode(['code' => 1, 'msg' => '修改失败'], true));
        }
        $data['project_c_list'] = Db::table('jz_project_c')->order('cid', 'desc')->select()->toArray();
        $data['project_total'] = Db::table('jz_project_total p')->join('jz_project_c c', 'p.cid=c.cid')->where('id', $id)->find();
        return view("/project/project_total_edit", $data);
    }

    // 项目情况删除
    public function project_total_del()
    {
        $id = Request::get('id', '');
        $res = Db::table('jz_project_total')->where('id', $id)->delete();
        if (!$res) {
            exit(json_encode(['code' => 1, 'msg' => '删除失败'], true));
        } else {
            exit(json_encode(['code' => 0, 'msg' => '删除成功'], true));
        }
    }

    // 项目情况添加保存
    public function project_total_add_save()
    {
        $data['add_time'] = Request::post('add_time', '');
        $data['r_name'] = Request::post('r_name', '');
        $data['cid'] = Request::post('cid', '');
        $data['p_name'] = Request::post('p_name', '');
        $data['p_money'] = Request::post('p_money', '');
        $data['t_money'] = Request::post('t_money', '');
        $data['is_ok'] = Request::post('is_ok', '');


        if ($data['add_time'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '时间不能为空'], true));
        }
        if ($data['r_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '负责人名称'], true));
        }
        if ($data['cid'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '分类不能为空'], true));
        }
        if ($data['p_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '模块名称不能为空'], true));
        }
        if ($data['p_money'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '金额不能为空'], true));
        }
        if ($data['t_money'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '金额不能为空'], true));
        }
        if ($data['is_ok'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '必填项不能为空'], true));
        }

        $res = Db::table('jz_project_total')->insert($data);
        if ($res) {
            exit(json_encode(['code' => 0, 'msg' => '添加成功'], true));
        } else {
            exit(json_encode(['code' => 1, 'msg' => '添加失败'], true));
        }
    }

    // 项目情况修改保存
    public function project_total_edit_save()
    {
        $id = Request::post('id', '');

        $data['add_time'] = Request::post('add_time', '');
        $data['r_name'] = Request::post('r_name', '');
        $data['cid'] = Request::post('cid', '');
        $data['p_name'] = Request::post('p_name', '');
        $data['p_money'] = Request::post('p_money', '');
        $data['t_money'] = Request::post('t_money', '');
        $data['is_ok'] = Request::post('is_ok', '');

        if ($id == '') {
            exit(json_encode(['code' => 1, 'msg' => '修改失败'], true));
        }
        if ($data['add_time'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '时间不能为空'], true));
        }
        if ($data['r_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '负责人名称'], true));
        }
        if ($data['cid'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '分类不能为空'], true));
        }
        if ($data['p_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '模块名称不能为空'], true));
        }
        if ($data['p_money'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '金额不能为空'], true));
        }
        if ($data['t_money'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '金额不能为空'], true));
        }
        if ($data['is_ok'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '必填项不能为空'], true));
        }

        $res = Db::table('jz_project_total')->where('id', $id)->update($data);
        exit(json_encode(['code' => 0, 'msg' => '修改成功'], true));
    }

    // 项目情况分类列表
    public function cat_list()
    {
        $data['project_c_list'] = Db::table('jz_project_c')->select()->toArray();
        return view('/project/cat_list', $data);
    }
    // 项目情况分类添加
    public function cat_add_save()
    {
        $data['c_name'] = Request::post('c_name', '');
        $data['icon_color'] = Request::post('icon_color', '');
        $data['c_add_time'] = date('Y-m-d', time());

        if ($data['c_name'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '项目类型不能为空'], true));
        }
        if ($data['icon_color'] == '') {
            exit(json_encode(['code' => 1, 'msg' => '图标颜色不能为空'], true));
        }

        $c_list = Db::table('jz_project_c')->select()->toArray();
        foreach ($c_list as $v) {
            if ($data['c_name'] == $v['c_name']) exit(json_encode(['code' => 1, 'msg' => '项目名称已存在'], true));
        }

        $res = Db::table('jz_project_c')->insert($data);
        if (!$res) exit(json_encode(['code' => 1, 'msg' => '添加失败'], true));

        exit(json_encode(['code' => 0, 'msg' => '添加成功'], true));
    }
    // 项目情况分类删除
    public function cat_del()
    {
        $cid = Request::post('cid', '');

        $c_list = Db::table('jz_project_total')->distinct(true)->field('cid')->select()->toArray();

        foreach ($c_list  as $c) {
            if ($c['cid'] == $cid) exit(json_encode(['code' => 1, 'msg' => '该类型已被占用'], true));
        }

        $res = Db::table('jz_project_c')->where('cid', $cid)->delete();
        if (!$res) exit(json_encode(['code' => 1, 'msg' => '删除失败'], true));

        exit(json_encode(['code' => 0, 'msg' => '删除成功'], true));
    }




    // 经费明细报表页面
    public function cost_statistics()
    {
        // 当前用户
        $admin = Session::get('admin'); //获取当前的用户信息
        $data['uname'] = $admin['uname'];
        return view('/project/cost_statistics', $data);
    }

    // 项目情况报表页面
    public function project_statistics()
    {
        // 当前用户
        $admin = Session::get('admin'); //获取当前的用户信息
        $data['uname'] = $admin['uname'];  //返回当前用户名

        // 请求页面时，默认带过去当前的年月
        $data['year1'] = request::get('year1', date("Y", time()));
        $data['month1'] = request::get('month1', date("m", time()));
        $data['year2'] = request::get('year2', date("Y", time()));

        //圆饼图 + 面板1 数据
        $data['t_money'] = Db::table('jz_project_total')
            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
            ->sum('t_money'); //计算当月的总费用
        $data['p_money'] = Db::table('jz_project_total')
            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
            ->sum('p_money'); //计算当月的已结余的费用

        // 根据年月选择器获取月份范围的数据
        $data['c_data'] = Db::table('jz_project_total t1')
            ->field('t1.cid,t2.c_name,t2.icon_color,SUM(t1.t_money) group_money,
            ROUND(SUM(t1.t_money)/' . $data['t_money'] . ',2)  money_rate')
            ->rightJoin('jz_project_c t2', 't1.cid = t2.cid')
            ->whereMonth('t1.add_time', $data['year1'] . '-' . $data['month1'])
            ->group('t1.cid')
            ->order('group_money', 'DESC')
            ->select()
            ->toArray();

        // 月支出排行
        $data['p_list'] = Db::table('jz_project_total t1')
            ->field('t1.r_name,t1.p_name,t1.t_money,t1.add_time,t2.c_name,t2.icon_color')
            ->Join('jz_project_c t2', 't1.cid = t2.cid')
            ->whereMonth('t1.add_time', $data['year1'] . '-' . $data['month1'])
            ->order('t1.t_money', 'DESC')
            ->limit('3')
            ->select()
            ->toArray();


        // 年经费情况
        $data['p_year_list'] = Db::table('jz_project_total')
            ->field('DATE_FORMAT(add_time,"%Y-%m") t_month,count(*) p_number,sum(p_money) t_p_money,sum(t_money) t_t_money,ROUND(sum(p_money)/sum(t_money),2)  t_money_rate')
            ->whereYear('add_time', $data['year2'])
            ->group('t_month')
            ->select()
            ->toArray();


        // 近6个月的经费情况
        // 情况一、月份小于6月份
        // 情况一、月份大于6月份
        for ($i = 0; $i < 6; $i++) {
            if (($data['month1'] - $i) > 0) {
                $data['m_money'][$i]['t_money'] = Db::table('jz_project_total')
                    ->whereMonth('add_time', $data['year1'] . '-' . ($data['month1'] - $i))
                    ->sum('t_money');
                $data['m_money'][$i]['month'] =  ($data['month1'] - $i);
                $data['m_money'][$i]['year'] =  $data['year1'];
            } else {
                $data['m_money'][$i]['t_money'] = Db::table('jz_project_total')
                    ->whereMonth('add_time', ($data['year1'] - 1) . '-' . ($data['month1'] - $i + 12))
                    ->sum('t_money');
                $data['m_money'][$i]['month'] =  ($data['month1'] - $i + 12);
                $data['m_money'][$i]['year'] =   ($data['year1'] - 1);
            }
        }
        // 测试
        // print_r(request::get(''));
        // print_r($data);
        return view('/project/project_statistics', $data);
    }

    // 分类数据表
    public function p_statistics_c()
    {
        $cid = Request::get('cid', '');
        $data['year1'] = Request::get('year1', '');
        $data['month1'] = Request::get('month1', '');
        $data['c_name'] = Request::get('c_name', '');

        $data['c_list'] = Db::table('jz_project_total')
            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
            ->where('cid', $cid)
            ->select()
            ->toArray();
        return view('/project/p_statistics_c', $data);
    }

    // 排行数据表
    public function p_statistics_t()
    {
        $data['year1'] = Request::get('year1', '');
        $data['month1'] = Request::get('month1', '');

        $data['p_list'] = Db::table('jz_project_total')
            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
            ->order('t_money', 'desc')
            ->select()
            ->toArray();
        return view('/project/p_statistics_t', $data);
    }
}