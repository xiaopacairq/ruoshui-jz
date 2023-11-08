<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>项目情况详细信息</title>
</head>
<!-- 引入layui组件库 -->
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/layui/layui.js"></script>
<style>
h1 {
    font-size: 35px;
    margin-bottom: 20px;

    font-family: '黑体';
    font-weight: bolder;
}

.box .box-item {
    padding: 10px 0;
    font-size: 16px;

    width: 400px;

    color: #2F4056;
}

.box .box-item span:first-of-type {
    display: inline-block;
    width: 50%;
}
</style>

<body style="padding:50px">
    <h1><?= $project_total['p_name'] ?></h1>
    <div class="box">
        <div class="box-item">
            <span><i class="layui-icon layui-icon-date"></i>记账时间</span>
            <span><?= $project_total['add_time'] ?></span>
        </div>
        <div class="box-item">
            <span># 序号</span>
            <span><?= $project_total['id'] ?></span>
        </div>
        <div class="box-item">
            <span>@ 项目负责人</span>
            <span><?= $project_total['r_name'] ?></span>
        </div>
        <div class="box-item">
            <span>@ 项目类型</span>
            <span
                style="padding:5px 10px;border-radius: 10px;background-color:<?= $project_total['icon_color'] ?>"><?= $project_total['c_name'] ?></span>
        </div>
        <div class=" box-item">
            <span> # 已结余费用</span>
            <span>CN￥ <?= $project_total['p_money'] ?></span>
        </div>
        <div class="box-item">
            <span> # 总费用</span>
            <span>CN￥ <?= $project_total['t_money'] ?></span>
        </div>
        <div class="box-item">
            <span> # 费用情况</span>
            <?php if ($project_total['is_ok'] == 0) : ?>
            <span style="padding:5px 10px;border-radius: 10px;background-color:rgb(219, 237, 219);">已结余</span>
            <?PHP elseif ($project_total['is_ok'] == 1) : ?>
            <span style="padding:5px 10px;border-radius: 10px;background-color:rgba(227, 226, 224, 0.5);">部分结余</span>
            <?php else : ?>
            <span style="padding:5px 10px;border-radius: 10px;background-color:#FFB800;">未结余</span>
            <?PHP endif; ?>
        </div>
    </div>
    <hr class="layui-border-black">

</body>

</html>