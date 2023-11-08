<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>项目情况报表</title>
</head>
<!-- 引入css样式文件 -->
<link rel="stylesheet" href="/static/css/project_statistics.css">
<!-- 引入layui组件库 -->
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/layui/layui.js"></script>
<!-- 设置网站图标 -->
<link rel="shortcut icon" href="/static/images/statistics.ico" type="image/x-icon">
<!-- 引入echart -->
<script src="/static/chart/echarts.simple.min.js"></script>
<script src="/static/chart/shine.js"></script>

<body>
    <!-- 页面头部 -->
    <div class="head">
        <div class="left">
            <a href="/project/index"><img src="/static/images/index.svg" alt="">若水学习会财务总表</a>
            <span>/</span>
            <a href="/project/project_statistics"><img src="/static/images/statistics.png" alt="">项目情况报表</a>
        </div>
        <div class="right"><i class="layui-icon layui-icon-user"></i> 用户名：<?= $uname ?></div>
    </div>
    <div class="title">
        <img src="/static/images/statistics.png" alt=""><span>项目情况报表</span>
    </div>
    <div class="layui-container">
        <div class="box-head"
            style="border-bottom: 1px solid #000;background-color: #1E9FFF;padding:10px;color:whitesmoke">
            <!-- 图标 -->
            <div class=""><i class="layui-icon layui-icon-component" style="font-size:30px;padding:0 10px"></i></div>
            <!-- 日期组件 -->
            <div id="date"
                style="height: 38px; line-height: 38px; cursor: pointer; border-bottom: 1px solid #000;font-size:30px">
            </div>
            <?php if ($t_money != 0) : ?>
            <div style="height:38px; line-height: 38px;font-size: 15px;margin-left:30px">
                已结余/总费用<?= $p_money ?>/<?= $t_money ?></div>
            <div style="height:38px; line-height: 38px;font-size: 15px;margin-left:30px">
                经费结算率：<?= (round($p_money / $t_money, 4)) * 100 ?>%</div>
            <?php endif; ?>
        </div>

        <!-- 月费用构成  -->
        <div class="box1">
            <div class="box1-head">月费用构成</div>
            <?php if ($t_money != 0) : ?>
            <div class="box1-item">
                <div class="" id="pie" style="width: 400px;height:400px;"></div>
                <div class="layui-card">
                    <div class="layui-card-header">各费用构成情况</div>
                    <div class="layui-card-body">
                        <?php foreach ($c_data as $c) : ?>
                        <a onclick='to_herf("<?= $c["cid"] ?>","<?= $c["c_name"] ?>")' class="card-item"
                            style="cursor: pointer">
                            <span style="width:40%;max-width:40%;min-width:40%;display:flex">
                                <i class="layui-icon layui-icon-read"
                                    style="color:<?= $c['icon_color'] ?>;padding:0 10px;"></i><span class="layui-elip"
                                    title="<?= $c['c_name'] ?>"><?= $c['c_name'] ?></span>
                            </span>
                            <div class="layui-progress" style="width:40%;max-width:40%;margin:0 10px"
                                lay-filter='progress'>
                                <span class="layui-progress-bar" lay-percent="<?= (($c['money_rate'] * 100) . '%') ?>"
                                    id="progress"></span>
                            </div>
                            <span style="width:20%;max-width:40%">￥<?= $c['group_money'] ?><i
                                    class="layui-icon layui-icon-right"></i></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <span class="layui-word-aux">当月无数据</span>
            <?php endif; ?>

        </div>

        <?php if ($t_money != 0) : ?>

        <!-- 月度对比 + 月排行mvp -->
        <div class="box2">
            <div class="box2-item">
                <div class="box2-head">
                    【
                    <?= $m_money[5]['year'] . '年' . $m_money[5]['month'] . '月' ?>
                    ~
                    <?= $m_money[0]['year'] . '年' . $m_money[0]['month'] . '月' ?>
                    】
                    月度对比
                </div>
                <div class="">
                    <div class="" id="bar" style="width: 600px;height:300px"></div>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-header"><?= $month1 ?>月项目费用排行</div>
                <div class="layui-card-body">
                    <?php foreach ($p_list as $k => $p) : ?>
                    <div class="card-item">
                        <div class="" style="width:10%"><?= $k + 1 ?></div>
                        <div class="" style="width:10%;">
                            <i class="layui-icon layui-icon-read"
                                style="color:<?= $p['icon_color'] ?>;font-size: 30px;font-weight:bolder"></i>
                        </div>
                        <div class="" style="min-width:60%;width:60%">
                            <p class="layui-elip" title="<?= $p['c_name'] ?>（<?= $p['r_name'] ?>）">
                                <?= $p['c_name'] ?>（<?= $p['r_name'] ?>）</p>
                            <p class="layui-elip" title="<?= $p['p_name'] ?>">
                                <?= $p['p_name'] ?></p>
                        </div>
                        <div class="" style="width:20%">
                            <div class="">￥<?= $p['t_money'] ?></div>
                            <div class=""><?= $p['add_time'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="foot"><a onclick="to_rank()">全部排行 <i class="layui-icon layui-icon-right"></i></a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="box-head"
            style="border-bottom: 1px solid #000;background-color: #1E9FFF;padding:10px;color:whitesmoke">
            <!-- 图标 -->
            <div class=""><i class="layui-icon layui-icon-component" style="font-size:30px;padding:0 10px"></i></div>
            <!-- 日期组件 -->
            <div id="year"
                style="height: 38px; line-height: 38px; cursor: pointer; border-bottom: 1px solid #000;font-size:30px">
            </div>
        </div>
        <!-- 年度对比 -->
        <div class="year">
            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th>年月</th>
                        <th>项目总数</th>
                        <th>已结余费用</th>
                        <th>总费用</th>
                        <th>经费结算率</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($p_year_list as $p_year) : ?>
                    <tr>
                        <td><?= $p_year['t_month'] ?></td>
                        <td><?= $p_year['p_number'] ?></td>
                        <td><?= $p_year['t_p_money'] ?></td>
                        <td><?= $p_year['t_t_money'] ?></td>
                        <td><?= $p_year['t_money_rate'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
    // 定义公共变量，初始值从后端获取
    var year1 = <?= $year1 ?>; //年月选择器值 年份值
    var month1 = <?= $month1 ?>; //年月选择器 月份值
    var year2 = <?= $year2 ?>; //年选择器的值

    // 开启layui组件
    var $ = layui.jquery;
    var laydate = layui.laydate;
    var layer = layui.layer;

    //月度时间切换
    laydate.render({
        elem: '#date',
        type: 'month', //值支持年月设置
        value: year1 + '年' + month1 + '月',
        format: 'yyyy年M月',
        btns: ['now', 'confirm'],
        theme: '#1E9FFF',
        done: function(value, date) {
            year1 = date.year;
            month1 = date.month;
            window.location.href = "/project/project_statistics?year1=" + year1 + '&month1=' +
                month1 + '&year2=' + year2; //点击切换日期后，带get请求页面
        },
    });

    // 点击查看细则分类
    function to_herf(cid, c_name) {
        layer.open({
            type: 2,
            title: '详细信息',
            offset: '60px',
            maxmin: true, //开启最大化最小化按钮
            area: ['900px', '600px'],
            shadeClose: true,
            shade: 0.3,
            content: '/project/p_statistics_c?cid=' + cid + '&year1=' + year1 + '&month1=' + month1 +
                '&c_name=' + c_name,
        });

    }

    // 全部排行
    function to_rank() {
        layer.open({
            type: 2,
            title: '费用排行',
            offset: '60px',
            maxmin: true, //开启最大化最小化按钮
            area: ['900px', '600px'],
            shadeClose: true,
            shade: 0.3,
            content: '/project/p_statistics_t?year1=' + year1 + '&month1=' + month1,
        });
    }

    //年度时间切换
    laydate.render({
        elem: '#year',
        type: 'year',
        value: year2 + '年',
        format: 'yyyy年',
        btns: ['now', 'confirm'],
        theme: '#1E9FFF',
        done: function(value, date) {
            year2 = date.year;

            window.location.href = "/project/project_statistics?year1=" + year1 + '&month1=' +
                month1 + '&year2=' + year2;
        }
    });

    // 月费用构成饼状图
    echarts.init(document.getElementById('pie'), 'shine').setOption({
        title: {
            text: '费用构成',
            left: 'center',
            top: 'center',
        },
        series: [{
            type: 'pie',
            data: [
                <?php foreach ($c_data as $c) : ?> {
                    value: "<?= $c['group_money'] ?>",
                    name: "<?= $c['c_name'] ?>",
                },
                <?php endforeach; ?>

            ],
            radius: ['20%', '40%']
        }]
    });


    // 近6个月的经费信息（柱状图）
    echarts.init(document.getElementById('bar'), 'shine').setOption({
        xAxis: {
            data: [
                <?php foreach ($m_money as $k => $v) {
                        echo $m_money[5 - $k]['month'] . ',';
                    } ?>

            ]
        },
        yAxis: {},
        showBackground: true,
        series: [{
            type: 'bar',
            data: [
                <?php foreach ($m_money as $k => $v) {
                        echo $m_money[5 - $k]['t_money'] . ',';
                    } ?>
            ]
        }]
    });
    </script>
</body>

</html>