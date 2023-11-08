<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>经费明细详细信息</title>
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
    <h1><?= $cost['remark'] ?></h1>
    <div class="box">
        <div class="box-item">
            <span><i class="layui-icon layui-icon-date"></i> 进账时间</span>
            <span><?= $cost['add_time'] ?></span>
        </div>
        <div class="box-item">
            <span># 序号</span>
            <span><?= $cost['id'] ?></span>
        </div>

        <div class="box-item">
            <span>@ 登记人</span>
            <span><?= $cost['u_name'] ?></span>
        </div>
        <div class="box-item">
            <span> # 进账金额</span>
            <span>￥ <?= $cost['u_money'] ?></span>
        </div>
    </div>
    <hr class="layui-border-black">

</body>

</html>