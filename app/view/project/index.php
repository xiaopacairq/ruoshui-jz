<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>若水学习会财务总表</title>
</head>
<!-- 引入login.css样式文件 -->
<link rel="stylesheet" href="/static/css/index.css">
<!-- 引入layui组件库 -->
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/layui/layui.js"></script>
<!-- 设置网站图标 -->
<link rel="shortcut icon" href="/static/images/index.ico" type="image/x-icon">


<body>
    <!-- 页面头部 -->
    <div class="head">
        <div class="left">
            <a href="/project/index"><img src="/static/images/index.svg" alt="">若水学习会财务总表</a>
        </div>
        <div class="right"><i class="layui-icon layui-icon-user"></i> 用户名：<?= $uname ?></div>
    </div>
    <div class="layui-container">
        <div class="head-main">
            <!-- 左侧图片 -->
            <img src="/static/images/finance.jpg" alt="">
            <!-- 右侧天气项 -->
            <div class="weather">
                <div
                    style="font-size:22px;font-weight: bolder;background-color:#31BDEC;color:white;padding:5px 20px;height:15%">
                    今日天气
                </div>
                <!-- 如果请求数据成功 -->
                <?php if ($is_show == 1) : ?>
                <div style="font-size:20px;font-weight: bolder;padding:5px 10px;height:10%"><i
                        class="layui-icon layui-icon-location"></i><?= $weather['result']['city'] ?></div>
                <div class="b-weather">
                    <div class="weather-left">
                        <div style="text-align:center">
                            <span
                                style="font-size: 50px;"><?= $weather['result']['realtime']['temperature'] ?></span><em>℃</em>
                        </div>
                        <?php if ($weather['result']['realtime']['aqi'] <= 50) : ?>
                        <div style="background-color:#8ccd26;border-radius:8px;color:white;text-align:center">
                            <?= $weather['result']['realtime']['aqi'] ?>优</div>
                        <?php elseif ($weather['result']['realtime']['aqi'] <= 100) : ?>
                        <div style="background-color:blue;border-radius:8px;color:white;text-align:center">
                            <?= $weather['result']['realtime']['aqi'] ?>良</div>
                        <?php elseif ($weather['result']['realtime']['aqi'] <= 200) : ?>
                        <div style="background-color:#c0ff00;border-radius:8px;color:white;text-align:center">
                            <?= $weather['result']['realtime']['aqi'] ?>轻度污染</div>
                        <?php elseif ($weather['result']['realtime']['aqi'] <= 300) : ?>
                        <div style="background-color:typhoon;border-radius:8px;color:white;text-align:center">
                            <?= $weather['result']['realtime']['aqi'] ?>中度污染</div>
                        <?php else : ?>
                        <div style="background-color:red;border-radius:8px;color:white;text-align:center">
                            <?= $weather['result']['realtime']['aqi'] ?>重度污染</div>
                        <?php endif; ?>
                    </div>
                    <div class="weather-right">
                        <div style="font-size:22px;"><?= $weather['result']['realtime']['info'] ?></div>
                        <div><i
                                class="layui-icon layui-icon-location"></i><?= $weather['result']['realtime']['direct'] ?>
                            <?= $weather['result']['realtime']['power'] ?></div>
                        <div><i class="layui-icon layui-icon-location"></i>相对湿度
                            <?= $weather['result']['realtime']['humidity'] ?>%</div>
                    </div>
                </div>
                <?php elseif ($is_show == 0) : ?>
                <!-- 请求数据失败 -->
                <div class="noshow"><i class="layui-icon layui-icon-face-cry"></i>数据请求失败！</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="layui-container">
        <!-- 主表部分 -->
        <div class="left">
            <div class="head-item item">若水学习会财务总表</div>
            <div class="item"><a href="/project/project_cost_list"><img src="/static/images/cost.svg"
                        alt="">学习会经费明细表</a></div>
            <div class="item"><a href="/project/project_total_list"><img
                        src="/static/images/project_total.svg" alt="">学习会项目情况表</a></div>
            <?php foreach ($c_list as $c) : ?>
            <div class="item">
                <a href="/project/project_total_list?cid=<?= $c['cid'] ?>">
                    <i class="layui-icon layui-icon-read" style="color:<?= $c['icon_color'] ?>"></i><?= $c['c_name'] ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- 右侧友情内容 -->
        <div class="right">
            <!-- 统计项 -->
            <div class="statisics">
                <p style="padding:0;"><i class="layui-icon layui-icon-survey" style="color:darkorange"></i> 网页统计</p>
                <hr class="layui-border-blue" style="margin:5px;">
                <p><i class="layui-icon layui-icon-triangle-r"></i> 经费记账总数 - <?= $cost_count ?> 条</p>
                <p><i class="layui-icon layui-icon-triangle-r"></i> 项目记账总数 - <?= $project_total_count ?> 条</p>
                <p><i class="layui-icon layui-icon-triangle-r"></i> 记账天数 - <?= $jz_time ?>天</p>
            </div>
            <!-- 报表分析 -->
            <div class="analysis">
                <p style="padding:0;"><i class="layui-icon layui-icon-table" style="color:cornflowerblue"></i> 报表分析</p>
                <hr class="layui-border-blue" style="margin:5px;">
                <p><a href="/project/project_statistics"><i class="layui-icon layui-icon-triangle-r"></i>
                        生成项目情况报表</a></p>
            </div>
            <!-- 友情链接 -->
            <div class="firend-link">
                <p style="padding:0;"><i class="layui-icon layui-icon-release" style="color:gold"></i> 友情链接</p>
                <hr class="layui-border-blue" style="margin:5px;">
                <div><a href="http://shenruiqing.cn/stu/" target="_blank"><i
                            class="layui-icon layui-icon-triangle-r"></i> 初级学生信息管理系统</a></div>
                <div><a href="http://shenruiqing.cn/csg/login.php" target="_blank"><i
                            class="layui-icon layui-icon-triangle-r"></i> 高级学生信息管理系统</a></div>
                <div><a href="https://shenruiqing.cn:1235/" target="_blank"><i
                            class="layui-icon layui-icon-triangle-r"></i> 橘猫商城后台管理系统</a></div>
            </div>
        </div>
    </div>
    <script>
    // 日期控件
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        //直接嵌套显示
        laydate.render({
            elem: '#test-n2',
            position: 'static',
            btns: ['clear'],
            calendar: true
        });
    });
    </script>
</body>

</html>