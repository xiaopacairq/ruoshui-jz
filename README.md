# 若水学习会记账系统

## 一、技术选型

1. 前端：原生HTML、CSS、JavaScript、layui（表单、表格、图标）、Ajax请求、layer弹窗

2. 后端：thinktop6框架技术、MySQL数据库服务器、Apache web服务器

3. 参考ui设计：https://spiny-scorpion-f5c.notion.site/90155b2bdbc746768e0002769f5311fd

4. 开发周期

   |   开发内容   | 开发时间        | 开发负责人 |
   | :----------: | --------------- | ---------- |
   | 模板框架配置 | 1月13日-1月16日 | 沈锐清     |
   |  数据库配置  | 1月16日-1月17日 | 沈锐清     |
   | 前端界面设计 | 1月16日-1月21日 | 沈锐清     |
   | 后端api设计  | 1月18日-1月25日 | 沈锐清     |
   |  服务器部署  |                 | 沈锐清     |
   |     上线     |                 | 沈锐清     |

## 二、页面原型ui设计

【图一：登录界面（login.php）】

![image-20230115113821627](images\image-20230115113821627.png)

【图二：记账主界面（index.php）】

![image-20230113101556730](images\image-20230113101556730.png)

【图三：项目情况总表（projectTotal.php）】

![image-20230113101932544](images\image-20230113101932544.png)

【图四：项目分表N（projectSlaveN.php）】![image-20230113102532850](images\image-20230113102532850.png)

【图五：费用明细总表（cost.php）】

![image-20230113102636028](C:\Users\28339\AppData\Roaming\Typora\typora-user-images\image-20230113102636028.png)

【图六：项目情况报告（project_tatistics.php）】

![image-20230126110434931](images\image-20230126110434931.png)

## 三、客户端页面功能设计

### （一）总体要求

1. 建立备份机制，就是部署两个一样的项目，一个用于测试、一个用于线上;
2. 写文档简化成什么问题 -怎么解决;
3. 将项目拉到github、gitee上，写部署文档；

### （二）账户

1. 界面内容：账号、密码、验证码；账号密码由管理员提供；

2. 主体功能：严格按照账号密码登录验证码进行登录、且不允许跨过登录界面进入其他界面；

3. 实现方式：layui提供表单技术、Ajax提供页面请求、PHP提供后端校验、验证码功能和登录校验功能；

   ![image-20230113112815138](images\image-20230113112815138.png)

### （三）项目情况界面

1. 界面内容：搜索框、表格、表单、layer弹窗、Ajax请求、打印、导出

2. 主体功能：项目数据CURD、分类表CURD、根据分类表生成分类子表

3. 实现方式：layui提供表单、表单、打印导出技术、Ajax提供页面请求、PHP提供CURD

   ![image-20230113235113503](images\image-20230113235113503.png)

   ![image-20230113232416320](images\image-20230113233050758.png)

### （四）经费明细界面

1. 界面内容：搜索框、表格、表单、layer弹窗、Ajax请求、打印、导出

2. 主体功能：经费明细CURD

3. 实现方式：layui提供表单、表单、打印导出技术、Ajax提供页面请求、PHP提供CURD；

   ![image-20230113230455720](images\image-20230113230455720.png)

### （五）项目统计报告

1. 界面内容：月费用构成模块、月项目排行、一年各月份对比模块、按月份选择

2. 各模块功能

   - 月费用构成模块（饼图）：根据项目类型进行分类：页头为月度总费用

   - 月项目排行（列表）：按照费用的大小进行排名，只展示前5名

   - 一年各月份对比模块（柱状图）：根据月份统计，比较一年来各月份的费用情况

   - 月度和年度报告：表格

   - 其他功能：根据月份进行筛选


### （六）经费明细报告

1. 暂未开发！！......................

## 四、项目步骤

### （一）建立网站

1. 使用phpstudy集成工具准备好php7.4.3、apache2.4.43

2. 安装composer2.4.2，安装tp6框架

   ```
   composer create-project topthink/think 【文件名】
   ```

3. 执行以public目录为入口目录（**遇到静态资源无法加载问题**）

   - apache配置入口文件【.htaccess】

     ```php
     <IfModule mod_rewrite.c>
       Options +FollowSymlinks -Multiviews
       RewriteEngine On
     
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
     
       RewriteRule ^(.*)$ index.php [L,E=PATH_INFO:$1]
     </IfModule>
     ```

   - nginx配置入口文件【nginx.htaccess】

     ```php
     location / {
       if (!-e $request_filename) {
         rewrite  ^(.*)$  /index.php?s=/$1  last;
       }
     }
     ```

4. 安装视图功能

   - 目的：支持前后端数据交互，且不跨过同源策略

   - 使用view模板引擎

     ```php
     composer require topthink/think-view
     ```

   - 修改view配置文件，默认后缀为php（可以用原生PHP语法）

     ```php
     // 模板后缀
     'view_suffix'  => 'php',
     ```

5. 开启报错信息，上线后要关闭

   - app.php -简单报错信息

     ```php
       *// 错误显示信息,非调试模式有效*
       'error_message'   => '页面错误！请稍后再试～',
       *// 显示错误信息*
       'show_error_msg'  => true,
     ```

   -  appservice.php  -详细报错信息 

     ```php
      *// 服务启动*
      $this->app->debug(true);
     ```

6. 开启全局session

   - 记录用户登录的信息

     ~~~php
     *// 全局中间件定义文件*
     return [
       *// 全局请求缓存*
       *// \think\middleware\CheckRequestCache::class,*
     
       *// 多语言加载*
       *// \think\middleware\LoadLangPack::class,*
     
       *// Session初始化*
       \think\middleware\SessionInit::class
     ];
   

### （二）页面及功能设计

#### 1.静态资源配置

- 安装layui-v2.7.26组件库：基于jquery；

- 安装echart.js图表库

  1. 项目地址：https://github.com/apache/echarts/tree/master/dist；
  2. 下载方式：因为采用单文件引入，所以下载echarts.simple.js最小的简易库；

  ![image-20230126111017459](images\image-20230126111017459.png)

- 配置css样式库：存放页面样式文件

  ![image-20230126111149658](images\image-20230126111149658.png)

- 配置公共图片库：存放页面图片和图标文件

  ![image-20230126111241390](images\image-20230126111241390.png)

- 配置公共js组件库：存放公共js库

#### 2. 登录界面设计

##### （1）界面设计效果

![image-20230115222702022](images\image-20230115222702022.png)

##### （2）技术点

* **技术点1：网站图标设置**

  > 网站图标默认加载public目录下的favicon.ico图标，可在菜鸟教程工具栏里将图片转为ico格式

  ~~~html
  <!-- 自定义设置网站图标 -->
  <link rel="shortcut icon" href="/static/images/index.ico" type="image/x-icon">
  ~~~

* **技术点2：布局对齐**

  > 采用layui的表单布局，无法实现验证码和图片与上面的账号密码框对齐，所以自定义布局
  >
  > 1. 去除了layui表单原有的layui-form-item类、layui-form-label类和layui-input-block类
  >
  > 2. 自定义类名，保持结构不变
  >
  >    ~~~html
  >    <div class="form-item">
  >    	<div class="label"><i class="layui-icon layui-icon-password"></i></div>
  >    	<div class="input-item">
  >    		<input type="password" name="pwd" required lay-verify="required" 
  >    		placeholder="请输入密码" autocomplete="off" class="layui-input">
  >    	</div>
  >    </div>
  >    ~~~
  >
  > 3. 定义label和input-item的大小，验证码的input-item则采用flex布局，目的是让其水平排列且不换行
  >
  >    ~~~css
  >    .label {
  >        width: 40px;
  >        font-size: 20px;
  >    }
  >    .input-item {
  >        width: 280px;
  >    }
  >    /* 此类与input-item一样，采用class="input-item captcha"写法 */
  >    .captcha {
  >        display: flex;
  >        flex-flow: row nowrap;
  >    }
  >    ~~~
  
* 技术点3：登录逻辑

  > 内部系统，不对密码进行加密，验证码使用tp框架内置的captcha模板：需要手动开启session
  >
  > 开启session（app/middleware.php），tp6默认是不开启session的。
  >
  > ~~~php
  > // 全局中间件定义文件
  > return [
  >     // 全局请求缓存
  >     // \think\middleware\CheckRequestCache::class,
  >     // 多语言加载
  >     // \think\middleware\LoadLangPack::class,
  >     // Session初始化
  >     \think\middleware\SessionInit::class
  > ];
  > ~~~
  >
  > ~~~php
  > use think\captcha\facade\Captcha;
  > use think\facade\Session;  //引入session类
  > // 注册验证码
  > public function verify()
  > {
  >     return Captcha::create();
  > }
  > ~~~
  >
  > ~~~html
  > 验证码生成图片 
  > <img id="captcha" src="/index.php/account/verify" alt="" onclick="reloadveriimg(this)">
  > ~~~
  >
  > ~~~js
  > // 刷新验证码函数：用于验证码错误刷新和点击刷新
  > function reloadveriimg(obj) {
  >     // 加入随机字符， 表示验证码图片已经发生改变
  >     $(obj).attr('src', '/index.php/account/verify?rand=' + Math.random());
  > }
  > ~~~
  >
  > 执行登录逻辑
  >
  > ~~~php
  > // 登录校验
  > public function do_login()
  > {
  > // 获取请求数据
  > $uname = Request::post('uname', '');
  > $pwd = Request::post('pwd', '');
  > $captcha = Request::post('captcha', '');
  > 
  > // 非空验证
  > if (!$uname) exit(json_encode(['code' => 1, 'msg' => '用户名不能为空'], true));
  > if (!$pwd) exit(json_encode(['code' => 1, 'msg' => '密码不能为空'], true));
  > if (!$captcha) exit(json_encode(['code' => 1, 'msg' => '验证码不能为空'], true));
  > 
  > // 验证码校验
  > if (!captcha_check($captcha)) exit(json_encode(['code' => 1, 'msg' => '验证码错误'], true));
  > 
  > // 用户名是否存在
  > $user = Db::table('jz_admin')->where('uname', $uname)->find();
  > if (!$user) exit(json_encode(['code' => 1, 'msg' => '用户名错误'], true));
  > 
  > // 密码校验
  > if ($user['pwd'] != $pwd) exit(json_encode(['code' => 1, 'msg' => '密码错误'], true));
  > 
  > // 登录成功
  > $data['last_time'] = date('Y-m-d H:i:s', time());
  > Db::table('jz_admin')->where('uname', $user['uname'])->update($data); // 修改登录时间
  > 
  > Session::set('admin', $user); //将登录信息保存到session中
  > Session::delete('captcha'); //删除验证码session
  > 
  > // exit会导致session失效
  > echo json_encode(['code' => 0, 'msg' => '登录成功'], true);
  > }

* 技术点4，对未登录的用户，阻止访问其他页面

  > 对于登录与内部功能界面，建立中间类Base.php，在里面进行登录校验
  >
  > 1.session保存的是验证码，最后保存的是用户信息
  >
  > ~~~php
  > use think\facade\Session;  //引入session类
  > 
  > // 执行登录类
  > class Account extends BaseController
  > {
  >     // 登录校验
  >     public function do_login()
  >     {
  >         // ......code
  >         // 通过登录
  >         Session::set('admin', $user); //将登录信息保存到session中
  >         Session::delete('captcha'); //删除验证码session
  > 
  >         // exit会导致session失效
  >         echo json_encode(['code' => 0, 'msg' => '登录成功'], true);
  >     }
  > }
  > 
  > // 登录校验类
  > class Base extends BaseController
  > {
  >      // 初始化：未登录的用户不允许进入
  >     protected function initialize()
  >     {
  >         $admin = Session::get('admin'); //获取account的do_login的session
  >         if (!$admin) {
  >             if (Request::isAjax()) {
  >                 exit(json_encode(['code' => 1, 'msg' => '你还未登录，请登录']));
  >             }
  >             exit('你还未登录，请登录
  >             <script>
  >                 setTimeout(function()
  >                 {window.parent.location.href="/index.php/account"},2000)
  >             </script>
  >             ');
  >         }
  >     }
  > }
  > 
  > //内部功能类
  > class Project extends Base
  > {
  >  //   ......code
  > }

#### 3.记账主页面设计

##### （1）界面效果

![image-20230118104011409](images\image-20230118104011409.png)

##### （2）技术点

- **技术点1：页面布局（前端）**

  > 目前无法实现移动端适配，页面分为三个模块：顶部导航栏、中间图片+天气组件和下部分各表
  >
  > 1. 采用layui-container容器布局，左右比例为 7.5 : 2 .5

- **技术点2：天气控件请求数据方法（后端）**

  > 天气控件：可采用json、jsonp、后端、nginx反向代理实现，但各有优缺点
  >
  > 1. 概念：同源策略：指 https:// ruoshui.cn ：1234中，各个部分都需保持一致才能实现请求，否则会遇到跨域请求的问题，Ajax是不支持跨域请求的。
  >
  > 2. 方法一：采用jsonp格式的Ajax请求，使用了script支持跨域请求的漏洞，但是很不稳定；
  >
  > 3. 方法二：使用PHP服务器的curl方法，配置即可跨域获取数据
  >
  >    ~~~php
  >    /**
  >    * 发起网络请求函数
  >    * @param $url 请求的URL
  >    * @param bool $params 请求的参数内容
  >    * @param int $ispost 是否POST请求
  >    * @return bool|string 返回内容
  >    */
  >    private function juheHttpRequest($url, $params = false, $ispost = 0)
  >    {
  >    $httpInfo = array();
  >    $ch = curl_init();
  >    
  >    //强制使用 HTTP/1.1
  >    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
  >    //在HTTP请求中包含一个"User-Agent: "头的字符串。
  >    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
  >    // 在尝试连接时等待的秒数。设置为0，则无限等待。
  >    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
  >    //允许 cURL 函数执行的最长秒数。
  >    curl_setopt($ch, CURLOPT_TIMEOUT, 12);
  >    // true 将curl_exec()获取的信息以字符串返回，而不是直接输出。
  >    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  >    
  >    // 如果传入的参数为1，则开启post请求
  >    if ($ispost) {
  >        // true 时会发送 POST 请求
  >        curl_setopt($ch, CURLOPT_POST, true);
  >        // 请求参数
  >        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  >        // 请求地址
  >        curl_setopt($ch, CURLOPT_URL, $url);
  >    } else {
  >        // 如果不传入参数，则为get请求
  >        if ($params) {
  >            // 如果有参数，则用？拼接get
  >            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
  >        } else {
  >            // 如果没有参数，直接使用get
  >            curl_setopt($ch, CURLOPT_URL, $url);
  >        }
  >    }
  >    // 执行curl会话
  >    $response = curl_exec($ch);
  >    
  >    // 若执行成功，则返回true，错误则返回false
  >    if ($response === FALSE) {
  >        // echo "cURL Error: ".curl_error($ch);
  >        return false;
  >    }
  >    //获取一个cURL连接资源句柄的信息
  >    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  >    //合并一个或多个数组
  >    $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
  >    // 结束curl请求
  >    curl_close($ch);
  >    return $response;
  >    }
  >    ~~~
  >
  > 4. 方法三：使用nginx反向代理，前端用的多，暂时未学

- **技术点3：天气控件请求数据参数**

  > 天气预报控件需要city：'汕头' 这个参数，城市参数需要从ip获取，ip需要通过php获取，所以需要执行的逻辑顺序如下：
  >
  > ![image-20230118105857832](images\image-20230118105857832.png)
  >
  > 1. 使用php获取当地的 ip 地址
  >
  >    ~~~php
  >    // 查询登陆地本地ip地址
  >    private function get_ip($type = 0, $adv = true)
  >    {
  >    $type = $type ? 1 : 0;
  >    static $ip = null;
  >    if (null !== $ip) {
  >        return $ip[$type];
  >    }
  >
  >    $httpAgentIp = Config::get('http_agent_ip');
  >
  >    if ($httpAgentIp && isset($_SERVER[$httpAgentIp])) {
  >        $ip = $_SERVER[$httpAgentIp];
  >    } elseif ($adv) {
  >        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  >            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
  >            $pos = array_search('unknown', $arr);
  >            if (false !== $pos) {
  >                unset($arr[$pos]);
  >            }
  >            $ip = trim(current($arr));
  >        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
  >            $ip = $_SERVER['HTTP_CLIENT_IP'];
  >        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
  >            $ip = $_SERVER['REMOTE_ADDR'];
  >        }
  >    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
  >        $ip = $_SERVER['REMOTE_ADDR'];
  >    }
  >    // IP地址合法验证
  >    $long = sprintf("%u", ip2long($ip));
  >    $ip   = $long ? [$ip, $long] : ['0.0.0.0', 0];
  >    return $ip[$type];
  >    }
  >    ~~~
  >
  > 2. 封装一个通用的聚合数据请求函数
  >
  >    ~~~php
  >    // 聚合请求数据
  >    private function get_data($url = '', $params = [])
  >    {
  >    $paramstring = http_build_query($params);
  >    $content = $this->juheHttpRequest($url, $paramstring, 1);
  >    $result = json_decode($content, true);
  >    if ($result) {
  >        if ($result['error_code'] == 0) {
  >            return $result;
  >        } else {
  >            return false;
  >        }
  >    } else {
  >        return false;
  >    }
  >    }
  >    ~~~
  >
  > 3. 先获取ip，再请求聚合数据获取ip的地区，再根据地区请求聚合数据获取天气情况
  >
  >    ~~~php
  >    // 获取本机ip信息
  >    $ip = $this->get_ip(0, true);  //上线后关闭
  >                                     
  >    // 根据ip地址获取地区信息
  >    $areas = $this->get_data('http://apis.juhe.cn/ip/ipNew', [
  >            "ip" => $ip , //需要查询的IP地址或域名
  >            "key" => 'appkey（可在聚合数据查到）', //应用APPKEY(应用详细页查询)
  >        ]);
  >        // 如果地区信息不存在或请求失败，则不展示;
  >        if (!$areas) {
  >            $data['is_show'] = 0;
  >            // print_r('获取地区失败');
  >        } else {
  >            // print_r('获取地区成功');
  >            if ($areas['error_code'] > 0) {
  >                // print_r('获取地区失败');
  >                $data['is_show'] = 0;
  >            } else {
  >                // print_r($areas);
  >                // 获取完整的地区名
  >                $area = $areas['result']['City'];
  >                                     
  >                // 截掉最后一个：区
  >                $area = substr($area, 0, strlen($area) - 3);
  >                                     
  >                // 如果地区信息存在的话，请求天气信息
  >                $weather = $this-
  >                    >get_data('http://apis.juhe.cn/simpleWeather/query', [
  >                    "city" => $area, //需要查询的城市名
  >                    "key" => 'appkey（可在聚合数据查到）', //应用APPKEY(应用详细页查询)
  >                ]);
  >                // 如果天气数据不存在或请求完了
  >                if (!$weather) {
  >                    $data['is_show'] = 0;
  >                    // print_r('获取天气失败');
  >                } else {
  >                    if ($weather['error_code'] > 0) {
  >                        $data['is_show'] = 0;
  >                        // print_r('获取天气失败');
  >                    } else {
  >                        $data['weather'] = $weather;
  >                        // print_r('获取天气成功');
  >                        // print_r($data['weather']);
  >                    }
  >                }
  >            }
  >        }
  >    ~~~
  
- 技术点3：项目总表分表生成逻辑

  > ![image-20230121122444132](images\image-20230121122444132.png)
  >
  > ![image-20230121122720148](images\image-20230121122720148.png)
  >
  > 总表和分表的区别在于是否传入cid参数，由此可以作为判断依据
  >
  > ~~~php
  > // 主页
  > public function index()
  > {
  >     // ....code
  > 
  >     // 生成分表数据
  >     $data['c_list'] = Db::table('jz_project_c')->order('cid', 'desc')->select()->toArray();
  > 
  >     return view('/project/index', $data);
  > }
  > ~~~
  >
  > ~~~php+html
  > <?php foreach ($c_list as $c) : ?>
  >     <div class="item">
  >         <a href="/index.php/project/project_total_list?cid=<?= $c['cid'] ?>">
  >             <i class="layui-icon layui-icon-read" style="color:<?= 
  >     		$c['icon_color'] ?>"></i><?= $c['c_name'] ?>
  >         </a>
  >     </div>
  > <?php endforeach; ?>
  
- 技术点4：侧边组件及网页统计数据加载(报表暂时忽略，在后面会单独讲)

  > ![image-20230119144428992](images\image-20230119144428992.png)
  >
  > ~~~php
  > // 主页
  > public function index()
  > {
  >     // ....code
  >     // 网页统计数据
  >     $data['cost_count'] = Db::table('jz_cost')->count();  //经费明细总数
  >     $data['project_total_count'] = Db::table('jz_project_total')->count();  //项目总数
  > 
  >     //记账天数 (开始时间为2023年3月)
  >     $start_time = strtotime('2022-1-1');
  >     $end_time = strtotime(date('Y-m-d', time()));
  >     $data['jz_time'] = abs(round(($end_time - $start_time) / 86400));
  > 
  >     return view('/project/index', $data);
  > }
  > ~~~
  >
  > ~~~html
  > <!-- 右侧友情内容 -->
  > <div class="right">
  >     <!-- 统计项 -->
  >     <div class="statisics">
  >         <p style="padding:0;"><i class="layui-icon layui-icon-survey" style="color:darkorange"></i> 网页统计</p>
  >         <hr class="layui-border-blue" style="margin:5px;">
  >         <p><i class="layui-icon layui-icon-triangle-r"></i> 经费记账总数 - <?= $cost_count ?> 条</p>
  >         <p><i class="layui-icon layui-icon-triangle-r"></i> 项目记账总数 - <?= $project_total_count ?> 条</p>
  >         <p><i class="layui-icon layui-icon-triangle-r"></i> 记账天数 - <?= $jz_time ?>天</p>
  >     </div>
  >     
  >     <!-- 报表分析 -->
  >     <div class="analysis">
  >         <p style="padding:0;">
  >             <i class="layui-icon layui-icon-table" style="color:cornflowerblue">			</i> 报表分析
  >         </p>
  >         <hr class="layui-border-blue" style="margin:5px;">
  >         <p><a href="">
  >             <i class="layui-icon layui-icon-triangle-r"></i> 生成项目情况报表
  >         </a></p>
  >         <p><a href="">
  >             <i class="layui-icon layui-icon-triangle-r"></i> 生成经费明细报表
  >         </a></p>
  >     </div>
  >     
  >     <!-- 友情链接 -->
  >     <div class="firend-link">
  >         <p style="padding:0;">
  >             <i class="layui-icon layui-icon-release" 
  >             style="color:gold"></i> 友情链接
  >         </p>
  >         <hr class="layui-border-blue" style="margin:5px;">
  >         <div><a href="https://shenruiqing.cn/stu/" target="_blank"><i
  >          class="layui-icon layui-icon-triangle-r"></i> 初级学生信息管理系统</a></div>
  >         <div><a href="https://shenruiqing.cn/stu2/login.php" target="_blank"><i
  >          class="layui-icon layui-icon-triangle-r"></i> 高级学生信息管理系统</a></div>
  >         <div><a href="https://shenruiqing.cn:1235/" target="_blank"><i
  >         class="layui-icon layui-icon-triangle-r"></i> 橘猫商城后台管理系统</a></div>
  >     </div>
  > </div>
  > ~~~

#### 4. 经费明细页面设计

##### （1）界面设计

![image-20230121123250797](images\image-20230121123250797.png)

##### （2）技术点

- 技术点1：数据表格的数据配置

  > 采用layui动态渲染的表格，数据需要从后端异步请求才能展示
  >
  > ~~~html
  > <!-- 动态渲染表格 -->
  > lay-filiter属性：用于对表格某一选中行的唯一标识，可获取该行数据
  > <table class="layui-hide" id="project_cost" lay-filter="test"></table>
  > ~~~
  >
  > ~~~js
  > table.render({
  > title: '若水学习会记账明细总表',  //定义表格名，用于导出
  > elem: '#project_cost',  //绑定表格
  > url: '/index.php/project/project_cost_data', //异步请求后端数据
  > response: { //后端请求数据json的必要参数格式
  >          statusName: 'code', //状态码
  >          statusCode: 0, //成功状态
  >          msgName: 'msg',  //状态信息
  >          dataName: 'data' , //请求数据
  >          method:'post'  //定义请求方式
  >          defaultToolbar: ['print', 'exports'],//定义打印和导出工具
  >          skin: 'line', //定义表格主题
  >         cols: [
  >             [{
  >                 type: 'radio',
  >                 width: 50,
  >                 minWidth: 50,
  >             }, {
  >                 field: 'id',
  >                 width: 100,
  >                 minWidth: 100,
  >                 title: '# 序号',
  >                 sort: true,
  >                 align: 'right'
  >             }, {
  >                 field: 'remark',
  >                 width: 550,
  >                 minWidth: 550,
  >                 title: 'Aa 信息备注',
  >             }, {
  >                 field: 'add_time',
  >                 width: 180,
  >                 minWidth: 180,
  >                 title: '进账时间',
  >                 sort: true,
  >                 align: 'center'
  >             }, {
  >                 field: 'u_money',
  >                 width: 180,
  >                 minWidth: 180,
  >                 title: '# 记账金额',
  >                 sort: true,
  >                 align: 'right',
  >                 templet: function(d) { //定义模板，灵活处理行数据
  >                     return '<span>CN￥' + d.u_money + '</span>';
  >                 }
  >             }, {
  >                 field: 'u_name',
  >                 title: '@ 登记人',
  >                 width: 150,
  >                 minWidth: 150,
  >             }]
  >         ],
  >            
  >      },
  > })
  > ~~~
  >
  > 定义双击事件，双击单元格后，通过id向后端请求：详细信息的数据
  >
  > ![image-20230121123325498](images\image-20230121123325498.png)
  >
  > ~~~js
  > //触发行双击事件，弹出详细信息
  > table.on('rowDouble(test)', function(obj) {
  >  // console.log(obj.tr) //得到当前行元素对象
  >  console.log(obj.data) //得到当前行数据
  >  var id = obj.data.id;
  >  layer.open({
  >      type: 2,
  >      title: '经费明细详细信息',
  >      offset: 'r',
  >      maxmin: false, //开启最大化最小化按钮
  >      area: ['500px', '100%'],
  >      shadeClose: true,
  >      shade: 0.3,
  >      anim: 5,
  >      scrollbar: false,
  >      content: '/index.php/project/project_cost_detail?id=' + id
  >  });
  > });
  > ~~~

- ------

- 技术点2：数据表格的检索

  > ![image-20230119133509389](images\image-20230119133509389.png)
  >
  > 定义检索栏于表格工具栏位置
  >
  > ~~~js
  > table.render({
  > 	toolbar: '#toolbarDemo',
  > })
  > ~~~
  >
  > 工具栏组件包括：
  >
  > - 搜索功能：检索备注框、检索登记人框、日期范围组件、搜索按钮、刷新按钮；
  > - 添加功能：添加数据按钮
  > - 结余金额展示框

  > ~~~js
  > <!-- 表格上部工具栏 -->
  > <script type="text/html" id="toolbarDemo">
  > <div class="layui-form">
  > <div class="layui-form-item">
  > <!-- 检索备注 -->
  > <div class="layui-inline">
  >  <input type="text" name="search_remark" placeholder="检索备注" autocomplete="off" class="layui-input">
  > </div>
  > <!-- 检索登记人 -->
  > <div class="layui-inline">
  >  <input type="text" name="search_u_name" placeholder="检索登记人" autocomplete="off" class="layui-input">
  > </div>
  > 
  > <!--  日期组件 -->
  > <div class="layui-inline">
  >  <div class="layui-inline" id="range-time">
  >      <div class="layui-input-inline">
  >          <input type="text" autocomplete="off" id="test-startDate-1" class="layui-input"
  >              placeholder="开始日期">
  >      </div>
  >      <div class="layui-form-mid">-</div>
  >      <div class="layui-input-inline">
  >          <input type="text" autocomplete="off" id="test-endDate-1" class="layui-input"
  >              placeholder="结束日期">
  >      </div>
  >  </div>
  > </div>
  > <!-- 搜索按钮 -->
  > <div class="layui-inline">
  >  <button class="layui-btn layui-btn-normal" onclick="search()"><i class="layui-icon layui-icon-search"
  >          style="font-size:24px"></i></button>
  > </div>
  > <!-- 返回按钮 -->
  > <div class="layui-inline">
  >  <button class="layui-btn layui-btn-primary layui-border-black" onclick="setTimeout(function() {
  >                      window.location.reload();
  >                  }, 1000);"><i class="layui-icon layui-icon-refresh"
  >          style="font-size:24px"></i></button>
  > </div>
  > <!-- 添加数据框 -->
  > <div class="layui-inline">
  >  <button class="layui-btn layui-btn-primary layui-border-black" onclick="add()"><i
  >          class="layui-icon layui-icon-add-1" style="font-size:30px"></i></button>
  > </div>
  > <!-- 剩余金额 -->
  > <div class="layui-inline">
  >  <button class="layui-btn layui-btn-disabled">
  >      <?php if ($t_money < 0) : ?>
  >      <span style="color:crimson;">结余金额：￥<?= $t_money ?></span>
  >      <?php else : ?>
  >      <span style="color:green;">结余金额：￥<?= $t_money ?></span>
  >      <?php endif; ?>
  >  </button>
  > </div>
  > </div>
  > </div>
  > </script>
  > ~~~
  >
  > 将检索数据通过get请求传输到后端，并刷新表格
  >
  > ~~~js
  > // 搜索
  > function search() {
  > search_remark = $.trim($('input[name="search_remark"]').val()); //获取备注框
  > search_u_name = $.trim($('input[name="search_u_name"]').val()); //获取登记人框
  > // console.log(start_time);  //测试行
  > // console.log(end_time);
  > // console.log(search_remark);
  > // console.log(search_u_name);
  > table.render({   //重新请求表格
  >  title: '若水学习会记账明细总表',
  >  elem: '#project_cost',
  >  url: '/index.php/project/project_cost_data',
  >  where: {  //get请求数据内容
  >      start_time,
  >      end_time,
  >      search_remark,
  >      search_u_name
  >  },
  >  toolbar: '#toolbarDemo',
  >  skin: 'line',
  >  defaultToolbar: ['print', 'exports'],
  >  page: false,
  >  method: 'get',
  >  response: {
  >      statusName: 'code',
  >      statusCode: 0,
  >      msgName: 'msg',
  >      dataName: 'data'
  >  },
  >  cols: [
  >      [{
  >          type: 'radio',
  >          width: 50,
  >          minWidth: 50,
  >      }, {
  >          field: 'id',
  >          width: 100,
  >          minWidth: 100,
  >          title: '# 序号',
  >          sort: true,
  >          align: 'right'
  >      }, {
  >          field: 'remark',
  >          width: 550,
  >          minWidth: 550,
  >          title: 'Aa 信息备注',
  >      }, {
  >          field: 'add_time',
  >          width: 180,
  >          minWidth: 180,
  >          title: '进账时间',
  >          sort: true,
  >          align: 'center'
  >      }, {
  >          field: 'u_money',
  >          width: 180,
  >          minWidth: 180,
  >          title: '# 记账金额',
  >          sort: true,
  >          align: 'right'
  >      }, {
  >          field: 'u_name',
  >          title: '@ 登记人',
  >          width: 150,
  >          minWidth: 150,
  >      }]
  >  ],
  > });
  >     
  > //将搜索内容返回给检索框
  > $.get('/index.php/project/project_cost_data', {
  >  start_time,
  >  end_time,
  >  search_remark,
  >  search_u_name
  > }, function(res) {
  >  console.log(res.search.search_remark);
  >  $('input[name="search_remark"]').val(res.search.search_remark);
  >  $('input[name="search_u_name"]').val(res.search.search_u_name);
  >  $('input[id="test-startDate-1"]').val(res.search.start_time);
  >  $('input[id="test-endDate-1"]').val(res.search.end_time);
  > }, 'json')
  > 
  > // 日期组件
  > laydate.render({
  >  elem: '#range-time',
  >  range: ['#test-startDate-1', '#test-endDate-1'],
  >  theme: '#31BDEC',
  >  calendar: true,
  >  done: function(value, s_time, e_time) {
  >      start_time = s_time.year + '-' + s_time.month + '-' + s_time.date;
  >      end_time = e_time.year + '-' + e_time.month + '-' + e_time.date;
  >      // console.log(s_time);
  >      // console.log(e_time);
  >      // console.log(value);
  >      // console.log(start_time);
  >      // console.log(start_time);
  >  }
  > });
  > }
  > ~~~
  >
  > 后端根据收集到的get请求参数，生成对应的where条件
  >
  > ~~~php
  > // 若水学习会经费明细总表:动态渲染表格数据
  > public function project_cost_data()
  > {
  > $where = []; //默认输入框检索条件为空
  > $whereTime = [0 => '1999-1-1', 1 => '2999-12-31']; //默认时间范围检索条件
  > 
  > // 检索条件
  > $start_time = Request::get('start_time', '');
  > $end_time = Request::get('end_time', '');
  > $search_remark = Request::get('search_remark', '');
  > $search_u_name = Request::get('search_u_name', '');
  > 
  > 但检索条件出现时，加入到DB的条件里
  > if ($start_time && $end_time) {
  >  $whereTime = array($start_time, $end_time);
  > }
  > if ($search_remark) {
  >  $where[] = ['remark', 'like', '%' . $search_remark . '%'];
  > }
  > if ($search_u_name) {
  >  $where[] = ['u_name', 'like', '%' . $search_u_name . '%'];
  > }
  > // print_r(Request::get(''));
  > 
  > $data['cost'] = Db::table('jz_cost')->where($where)->whereTime('add_time', 'between', $whereTime)->order('id', 'desc')->select()->toArray();
  > 
  > // dump($data);
  > exit(json_encode(['code' => 0, 'msg' => '请求成功', 'data' => $data['cost'], 'search' => Request::get('')], true));
  > }

- ------

- 技术点3：添加数据配置

  > ![image-20230121123425479](images\image-20230121123425479.png)
  >
  > 请求页面
  >
  > ~~~js
  > // 添加
  > function add() { //请求添加页面
  >     layer.open({
  >         type: 2,
  >         title: '经费明细添加',
  >         offset: 'r',
  >         maxmin: false, //开启最大化最小化按钮
  >         area: ['400px', '100%'],
  >         shadeClose: false,
  >         shade: 0.3,
  >         anim: 5,
  >         content: '/index.php/project/project_cost_add'
  >     });
  > }
  > ~~~



- 技术点4：金额框配置(输入合法金额)

  > ![image-20230121123637717](images\image-20230121123637717.png)
  >
  > ~~~html
  > <input type="text" id="u_money" name="u_money" placeholder="格式：199.99" 
  >     class="layui-input" required lay-verify="required" autocomplete="off">
  > ~~~
  >
  > ~~~javascript
  > // 开启金额控制组件
  > $("#u_money").numbox({});
  > 
  > // 金额输入框校验
  > (function($) {
  > // 数值输入框
  > $.fn.numbox = function(options) {
  >  var type = (typeof options);
  >  if (type == 'object') {
  >      // 创建numbox对象
  >      if (options.width) this.width(options.width);
  >      if (options.height) this.height(options.height);
  >      this.bind("input propertychange", function(obj) {
  >          numbox_propertychange(obj.target);
  >      });
  >      this.bind("change", function(obj) {
  >          var onChange = options.onChange;
  >          if (!onChange) return;
  >          var numValue = Number(obj.target.value);
  >          onChange(numValue);
  >      });
  >      this.bind("hide", function(obj) {
  >          var onHide = options.onHide;
  >          if (!onHide) return;
  >          var numValue = Number(obj.target.value);
  >          onHide(numValue);
  >      });
  >      return this;
  >  } else if (type == 'string') {
  >      // type为字符串类型，代表调用numbox对象中的方法
  >      var method = eval(options);
  >      if (method) return method(this, arguments);
  >  }
  > }
  > // 属性值变化事件
  > function numbox_propertychange(numbox) {
  >  if (numbox.value == '-' || numbox.value == numbox.oldvalue) return;
  >  var numvalue = Number(numbox.value);
  >  if (isNaN(numvalue)) {
  >      numbox.value = numbox.oldvalue;
  >  } else {
  >      numbox.oldvalue = numbox.value;
  >  }
  > }
  > // 获取值
  > function getValue(numbox) {
  >  var value = numbox.val();
  >  return Number(value);
  > }
  > // 设置值
  > function setValue(numbox, params) {
  >  if (params[1] == undefined) return;
  >  var numvalue = Number(params[1]);
  >  if (!isNaN(numvalue)) {
  >      for (var i = 0; i < numbox.length; i++) {
  >          numbox[i].focus();
  >          numbox[i].value = numvalue;
  >          numbox[i].oldvalue = numvalue;
  >      }
  >  }
  > }
  > })($);
  > ~~~

- 技术点5：修改删除数据参数

  > 表格的每一行都有一个单选按钮，点击单选按钮后，触发点击事件，获取当前行的id值
  >
  > ![image-20230119145017919](images\image-20230119145017919.png)
  >
  > ~~~js
  > // 点击单选按钮，获取数据的id，用于修改和删除
  > table.on('radio(test)', function(obj) { //test 是 table 标签对应的 lay-filter 属性
  >     // console.log(obj); //当前行的一些常用操作集合
  >     // console.log(obj.checked); //当前是否选中状态
  >     // console.log(obj.data); //选中行的相关数据
  >     id = obj.data.id;  //获取选中行的id
  >     layer.msg('请右下选择操作类型!');
  > });
  > ~~~

  

  > 开启删除和编辑模式
  >
  > ![image-20230119145054082](images\image-20230119145054082.png)
  >
  > ![image-20230121123944812](images\image-20230121123944812.png)
  >
  > ~~~js
  > //固定块 :执行修改和删除功能
  > util.fixbar({
  > bar1: '&#xe642',
  > bar2: '&#xe640',
  > css: {
  > right: 50,
  > bottom: 100
  > },
  > bgcolor: '#393D49',
  > click: function(type) {
  > if (type === 'bar1') {
  > 
  > if (id == '') {
  >  // 当没有选中数据时
  >  layer.msg('请选择一条数据！');
  > } else {
  >  // 修改
  >  layer.open({
  >      type: 2,
  >      offset: 'r',
  >      maxmin: false, //开启最大化最小化按钮
  >      area: ['400px', '100%'],
  >      shadeClose: false,
  >      shade: 0.3,
  >      anim: 5,
  >      title: '经费明细修改',
  >      content: '/index.php/project/project_cost_edit?id=' + id  //id为当前行的id
  >  });
  > }
  > } else if (type === 'bar2') {
  > if (id == '') {
  >  // 当没有选中数据时
  >  layer.msg('请选择一条数据！');
  > } else {
  >  // 删除
  >  layer.confirm('确定要删除该数据吗？', {
  >      btn: ['确认', '取消'] //按钮
  >  }, function() {
  >      $.get('/index.php/project/project_cost_del', {
  >          id  //id为当前行的id
  >      }, function(res) {
  >          console.log(res);
  >          if (res.code > 0) {
  >              layer.msg(res.msg, {
  >                  icon: 2
  >              });
  >          } else {
  >              layer.msg(res.msg, {
  >                  icon: 1
  >              });
  >              setTimeout(function() {
  >                  window.location.reload();
  >              }, 1000);
  >          }
  >      }, 'json')
  >  });
  > }
  > }
  > }
  > })；
  > ~~~

- 技术点6：结余金额计算

  > ![image-20230121124029511](images\image-20230121124029511.png)
  >
  > ~~~php
  > // 若水学习会经费明细总表
  > public function project_cost_list()
  > {
  >  $data['t_money'] = Db::table('jz_cost')->field('u_money')->sum('u_money');
  >  return view("/project/project_cost_list", $data);
  > }

#### 5.项目情况总表及分表设计

##### （1）界面效果

![image-20230120114303522](images\image-20230120114303522.png)

##### （2）技术点

- 技术点1：主表与分表的生成

> 主表：增删查改功能
>
> 分表：删除修改功能
>
> ![image-20230121124537997](images\image-20230121124537997.png)
>
> 
>
> ![image-20230121124523004](images\image-20230121124523004.png)
>
> ~~~php+html
> <?php if (isset($project_c_list['cid'])) : ?>
> 	/* 是否展示的代码 */
> <?php endif; ?>
> ~~~
>
> ~~~php
> // 项目情况总表 + 分表
> public function project_total_list()
> {
>     // ...code
>     // 分表依据:无此项就是总表
>     $cid = Request::get('cid', '');
>      if ($cid) {
>          $data['project_c_list'] = Db::table('jz_project_c')->where('cid', $cid)->find();
>      }
>     return view("/project/project_total_list", $data);
> }
> ~~~
>
> ~~~php
> // 项目情况数据
> public function project_total_data()
> {
>     // ...code
>     
>      // 分类表条件:有则筛选出该分类的数据
>     $cid = Request::get('cid', '');
> 
>       if ($cid) {
>         $where[] = ['p.cid', '=', $cid];
>     }
>     
>     // select
> }
> ~~~

- 技术点2，分类数据添加和删除

> ![image-20230121125418497](images\image-20230121125418497.png)
>
> 在添加和修改的界面，可以添加分类
>
> ~~~js
> // 添加分类
> function add_cat() {
>     layer.open({
>         type: 2,
>         title: '项目类型表',
>         skin: 'layui-layer-lan',
>         maxmin: false, //开启最大化最小化按钮
>         area: ['450px', '80%'],
>         shadeClose: false,
>         shade: 0.3,
>         anim: 5,
>         content: '/index.php/project/cat_list',
>         cancel: function() { //关闭后刷新页面，数据回显
>             setTimeout(function() {
>                 window.location.reload()
>             }, 1000);
>         }
>     });
> }
> ~~~
>
> 数据添加字段有项目类型名和图标的颜色，这里就不展示了。
>
> 数据的删除，需要检查是否该分类下已经存在数据。
>
> ~~~php
> // 项目情况分类删除
> public function cat_del()
> {
>     $cid = Request::post('cid', '');
> 
>     $c_list = Db::table('jz_project_total')->distinct(true)->field('cid')->select()->toArray();
> 
>     // 验证分类是否被占用
>     foreach ($c_list  as $c) {
>         if ($c['cid'] == $cid) exit(json_encode(['code' => 1, 'msg' => '该类型已被占用'], true));
>     }
> 
>     $res = Db::table('jz_project_c')->where('cid', $cid)->delete();
>     if (!$res) exit(json_encode(['code' => 1, 'msg' => '删除失败'], true));
> 
>     exit(json_encode(['code' => 0, 'msg' => '删除成功'], true));
> }
> ~~~

#### 6.项目情况报表设计

##### （1）界面效果

![image-20230126111531856](images\image-20230126111531856.png)

![image-20230126111549204](images\image-20230126111549204.png)

![image-20230126111608519](images\image-20230126111608519.png)

##### （2）技术点

- 技术点1：日期选择器上半区数据获取

  > ![image-20230126111531856](images\image-20230126111531856.png)
  >
  > 1. 日期选择器配置
  >
  >    ~~~html
  >    <div id="date" style="height: 38px; line-height: 38px; cursor: pointer; border-bottom: 1px solid #000;font-size:30px">
  >    ~~~
  >
  >    ~~~js
  >    // 定义公共变量，初始值从后端获取
  >    var year1 = <?= $year1 ?>; //年月选择器值 年份值
  >    var month1 = <?= $month1 ?>; //年月选择器 月份值
  >    var year2 = <?= $year2 ?>; //年选择器的值
  >    
  >    // 开启layui组件
  >    var $ = layui.jquery;
  >    var laydate = layui.laydate;
  >    var layer = layui.layer;
  >    
  >    //月度时间切换
  >    laydate.render({
  >        elem: '#date',
  >        type: 'month', //值支持年月设置
  >        value: year1 + '年' + month1 + '月',
  >        format: 'yyyy年M月',
  >        btns: ['now', 'confirm'],
  >        theme: '#1E9FFF',
  >        done: function(value, date) {
  >            year1 = date.year;
  >            month1 = date.month; //点击切换日期后，带get请求页面
  >            window.location.href = "/index.php/project/project_statistics?year1=" 
  >                + year1 + '&month1=' + month1 + '&year2=' + year2;  
  >            
  >        },
  >    });
  >    ~~~
  >
  >    ~~~php
  >    // 项目情况报表页面
  >    public function project_statistics()
  >    {    
  >        // 请求页面时，默认带过去当前的年月
  >        $data['year1'] = request::get('year1', date("Y", time()));
  >        $data['month1'] = request::get('month1', date("m", time()));
  >        $data['year2'] = request::get('year2', date("Y", time()));
  >        
  >        return view('/project/project_statistics', $data);
  >    }
  >    ~~~
  >
  > 2. 已结余费用、总费用、结算率数据获取
  >
  >    ~~~php+html
  >    <?php if ($t_money != 0) : ?> /* 如果没有数据，则不显示 */
  >    <div style="height:38px; line-height: 38px;font-size: 15px;margin-left:30px">
  >        已结余/总费用<?= $p_money ?>/<?= $t_money ?></div>
  >    <div style="height:38px; line-height: 38px;font-size: 15px;margin-left:30px">
  >        经费结算率：<?= (round($p_money / $t_money, 4)) * 100 ?>%</div>
  >    <?php endif; ?>
  >    ~~~
  >
  >    ~~~php
  >    // 项目情况报表页面
  >    public function project_statistics()
  >    {    
  >        $data['t_money'] = Db::table('jz_project_total')
  >            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
  >            ->sum('t_money'); //计算当月的总费用
  >        $data['p_money'] = Db::table('jz_project_total')
  >            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
  >            ->sum('p_money'); //计算当月的已结余的费用
  >        
  >        return view('/project/project_statistics', $data);
  >    }
  >    ~~~
  >
  > 3. 月费用构成数据获取
  >
  >    ![image-20230126113555465](images\image-20230126113555465.png)
  >
  >    ![image-20230126113528044](images\image-20230126113528044.png)
  >
  >    ~~~html
  >    <!-- 月费用构成  -->
  >    <div class="box1">
  >        <div class="box1-head">月费用构成</div>
  >                          
  >        <?php if ($t_money != 0) : ?>
  >        <div class="box1-item">
  >            <div class="" id="pie" style="width: 400px;height:400px;"></div>
  >            <div class="layui-card">
  >                <div class="layui-card-header">各费用构成情况</div>
  >                <div class="layui-card-body">
  >                                      
  >                    <?php foreach ($c_data as $c) : ?>
  >                    <a onclick='to_herf("<?= $c["cid"] ?>","<?= $c["c_name"] ?
  >    					>")' class="card-item"style="cursor: pointer">
  >                        <span style="width:40%;max-width:40%;min-
  >                                     width:40%;display:flex">
  >                            <i class="layui-icon layui-icon-read"
  >                                style="color:<?= $c['icon_color'] ?>;padding:0 
  >                                       10px;"></i><span class="layui-elip"
  >                                title="<?= $c['c_name'] ?>"><?= $c['c_name'] ?>
  >                            </span>
  >                        </span>
  >                        <div class="layui-progress" style="width:40%;max-
  >    						width:40%;margin:0 10px" lay-filter='progress'>
  >                            <span class="layui-progress-bar" lay-percent="<?= 						(($c['money_rate'] * 100) . '%') ?>"id="progress"></span>
  >                        </div>
  >                        <span style="width:20%;max-width:40%">￥<?= 
  >    				     $c['group_money'] ?><i class="layui-icon layui-icon-						right"></i></span>
  >                    </a>
  >                    <?php endforeach; ?>
  >                                      
  >                </div>
  >            </div>
  >        </div>
  >        <?php else : ?>
  >        <span class="layui-word-aux">当月无数据</span>  //当整个月份没有数据时
  >        <?php endif; ?>
  >                          
  >    </div>
  >    ~~~
  >
  >    ~~~js
  >    // 点击查看细则分类
  >    function to_herf(cid, c_name) {
  >        layer.open({
  >            type: 2,
  >            title: '详细信息',
  >            offset: '60px',
  >            maxmin: true, //开启最大化最小化按钮
  >            area: ['900px', '600px'],
  >            shadeClose: true,
  >            shade: 0.3,
  >            content: '/index.php/project/p_statistics_c?cid=' + cid + '&year1=' + 
  >            year1 + '&month1=' + month1 + '&c_name=' + c_name,
  >        });
  >    }
  >                      
  >    // 月费用构成饼状图
  >    echarts.init(document.getElementById('pie'), 'shine').setOption({
  >        title: {
  >            text: '费用构成',
  >            left: 'center',
  >            top: 'center',
  >        },
  >        series: [{
  >            type: 'pie',
  >            data: [
  >                <?php foreach ($c_data as $c) : ?> {
  >                    value: "<?= $c['group_money'] ?>",
  >                    name: "<?= $c['c_name'] ?>",
  >                },
  >                <?php endforeach; ?>
  >                      
  >            ],
  >            radius: ['20%', '40%']
  >        }]
  >    });
  >    ~~~
  >
  >    ~~~php
  >    // 项目情况报表页面
  >    public function project_statistics()
  >    {    
  >    // 根据年月选择器获取月份范围的数据
  >        $data['c_data'] = Db::table('jz_project_total t1')
  >            ->field('t1.cid,t2.c_name,t2.icon_color,SUM(t1.t_money) group_money,
  >            ROUND(SUM(t1.t_money)/' . $data['t_money'] . ',2)  money_rate')
  >            ->rightJoin('jz_project_c t2', 't1.cid = t2.cid')
  >            ->whereMonth('t1.add_time', $data['year1'] . '-' . $data['month1'])
  >            ->group('t1.cid')
  >            ->order('group_money', 'DESC')
  >            ->select()
  >            ->toArray();
  >                          
  >        return view('/project/project_statistics', $data);
  >    }
  >    
  >    
  >    // 分类数据表详细表
  >    public function p_statistics_c()
  >    {
  >        $cid = Request::get('cid', '');
  >        $data['year1'] = Request::get('year1', '');
  >        $data['month1'] = Request::get('month1', '');
  >        $data['c_name'] = Request::get('c_name', '');
  >    
  >        $data['c_list'] = Db::table('jz_project_total')
  >            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
  >            ->where('cid', $cid)
  >            ->select()
  >            ->toArray();
  >        return view('/project/p_statistics_c', $data);
  >    }
  >    ~~~
  >
  > 4. 近6月月度对比
  >
  >    ![image-20230126114304366](images\image-20230126114304366.png)
  >
  >    ~~~js
  >    // 近6个月的经费信息（柱状图）
  >    echarts.init(document.getElementById('bar'), 'shine').setOption({
  >        xAxis: {
  >        data: [
  >        <?php foreach ($m_money as $k => $v) {
  >                echo $m_money[5 - $k]['month'] . ',';
  >            } ?>
  >    
  >        ]
  >        },
  >        yAxis: {},
  >        showBackground: true,
  >        series: [{
  >        type: 'bar',
  >        data: [
  >        <?php foreach ($m_money as $k => $v) {
  >                echo $m_money[5 - $k]['t_money'] . ',';
  >            } ?>
  >        ]
  >        }]
  >    });
  >    ~~~
  >
  >    ~~~php
  >    // 近6个月的经费情况
  >    // 情况一、月份小于6月份 月份 +12 年份 -1
  >    // 情况二、月份大于6月份 
  >    for ($i = 0; $i < 6; $i++) {
  >        if (($data['month1'] - $i) > 0) {
  >            $data['m_money'][$i]['t_money'] = Db::table('jz_project_total')
  >                ->whereMonth('add_time', $data['year1'] . '-' . ($data['month1'] - $i))
  >                ->sum('t_money');
  >            $data['m_money'][$i]['month'] =  ($data['month1'] - $i);
  >            $data['m_money'][$i]['year'] =  $data['year1'];
  >        } else {
  >            $data['m_money'][$i]['t_money'] = Db::table('jz_project_total')
  >                ->whereMonth('add_time', ($data['year1'] - 1) . '-' . ($data['month1'] - $i + 12))
  >                ->sum('t_money');
  >            $data['m_money'][$i]['month'] =  ($data['month1'] - $i + 12);
  >            $data['m_money'][$i]['year'] =   ($data['year1'] - 1);
  >        }
  >    }
  >
  > 5. 月项目费用排行
  >
  >    ![image-20230126114316535](images\image-20230126114316535.png)
  >
  >    ![image-20230126114330890](images\image-20230126114330890.png)
  >
  >    ~~~html
  >    <div class="layui-card">
  >        <div class="layui-card-header"><?= $month1 ?>月项目费用排行</div>
  >        <div class="layui-card-body">
  >            <?php foreach ($p_list as $k => $p) : ?>
  >            <div class="card-item">
  >                <div class="" style="width:10%"><?= $k + 1 ?></div>
  >                <div class="" style="width:10%;">
  >                    <i class="layui-icon layui-icon-read"
  >                        style="color:<?= $p['icon_color'] ?>;font-size: 30px;font-weight:bolder"></i>
  >                </div>
  >                <div class="" style="min-width:60%;width:60%">
  >                    <p class="layui-elip" title="<?= $p['c_name'] ?>（<?= $p['r_name'] ?>）">
  >                        <?= $p['c_name'] ?>（<?= $p['r_name'] ?>）</p>
  >                    <p class="layui-elip" title="<?= $p['p_name'] ?>">
  >                        <?= $p['p_name'] ?></p>
  >                </div>
  >                <div class="" style="width:20%">
  >                    <div class="">￥<?= $p['t_money'] ?></div>
  >                    <div class=""><?= $p['add_time'] ?></div>
  >                </div>
  >            </div>
  >            <?php endforeach; ?>
  >        </div>
  >        <div class="foot"><a onclick="to_rank()">全部排行 <i class="layui-icon layui-icon-right"></i></a>
  >        </div>
  >    </div>
  >    ~~~
  >
  >    ~~~js
  >    // 全部排行
  >    function to_rank() {
  >        layer.open({
  >            type: 2,
  >            title: '费用排行',
  >            offset: '60px',
  >            maxmin: true, //开启最大化最小化按钮
  >            area: ['900px', '600px'],
  >            shadeClose: true,
  >            shade: 0.3,
  >            content: '/index.php/project/p_statistics_t?year1=' + year1 + '&month1=' + month1,
  >        });
  >    }
  >    ~~~
  >
  >    ~~~php
  >    // 项目情况报表页面
  >    public function project_statistics()
  >    {
  >        // 月支出排行
  >        $data['p_list'] = Db::table('jz_project_total t1')
  >            ->field('t1.r_name,t1.p_name,t1.t_money,t1.add_time,t2.c_name,t2.icon_color')
  >            ->Join('jz_project_c t2', 't1.cid = t2.cid')
  >            ->whereMonth('t1.add_time', $data['year1'] . '-' . $data['month1'])
  >            ->order('t1.t_money', 'DESC')
  >            ->limit('3')  //只查出前三名
  >            ->select()
  >            ->toArray();
  >        return view('/project/project_statistics', $data);
  >    }
  >    // 排行数据表
  >    public function p_statistics_t()
  >    {
  >        $data['year1'] = Request::get('year1', '');
  >        $data['month1'] = Request::get('month1', '');
  >    
  >        $data['p_list'] = Db::table('jz_project_total')
  >            ->whereMonth('add_time', $data['year1'] . '-' . $data['month1'])
  >            ->order('t_money', 'desc')
  >            ->select()
  >            ->toArray();
  >        return view('/project/p_statistics_t', $data);
  >    }
  >    ~~~

- 技术点2：年数据

  > ![image-20230126114632103](images\image-20230126114632103.png)
  >
  > 1. 年数据获取
  >
  >    ~~~js
  >    var year2 = <?= $year2 ?>; //年选择器的值 
  >                      
  >    //年度时间切换
  >    laydate.render({
  >        elem: '#year',
  >        type: 'year',
  >        value: year2 + '年',
  >        format: 'yyyy年',
  >        btns: ['now', 'confirm'],
  >        theme: '#1E9FFF',
  >        done: function(value, date) {
  >            year2 = date.year;
  >                      
  >            window.location.href = "/index.php/project/project_statistics?year1=" + year1 + '&month1=' +
  >                month1 + '&year2=' + year2;
  >        }
  >    });
  >    ~~~
  >
  >    ~~~php
  >    // 年经费情况
  >    $data['p_year_list'] = Db::table('jz_project_total')
  >        ->field('DATE_FORMAT(add_time,"%Y-%m") t_month,count(*) p_number,sum(p_money) t_p_money,sum(t_money) t_t_money,ROUND(sum(p_money)/sum(t_money),2)  t_money_rate')
  >        ->whereYear('add_time', $data['year2'])
  >        ->group('t_month')
  >        ->select()
  >        ->toArray();

## 出现问题

#### 1、入口文件隐藏问题：

开启入口文件隐藏后，加载静态文件失败问题：怀疑是tp的版本问题；

#### 2、CROS跨域请求问题：

请求数据的时候，协议阻止了数据请求，使用jsonp不靠谱，已用PHP解决同源问题；

jsonp是一种比较原始的解决跨域问题的方式，但是现在流行的方式1、nginx反向代理2、后端解决CROS

#### 3、虚拟ip上线后关闭

#### 4、不知道怎么写文档了

效果图+技术点

#### 5、无法实现实时的金额结余计算

#### 6、引入的金额输入框文件不知道怎么放到公共js库里

#### 7.select刷新后如何保存选中项问题

#### 8.别老是抓着前端不放

#### 9.分类颜色太深不适合区分

考虑是否有颜色生成器，或者自动加透明色

#### 10.left join失效问题

#### 11.记账的金额一定为正数
