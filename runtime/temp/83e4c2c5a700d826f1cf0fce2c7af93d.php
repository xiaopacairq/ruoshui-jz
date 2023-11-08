<?php /*a:1:{s:75:"D:\phpstudy_pro\WWW\ruoshui-jz-linux.cn\app\view\project\p_statistics_t.php";i:1674641567;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>排行</title>
    <!-- 引入layui组件库 -->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
</head>

<body>
    <h1 style="padding: 10px;text-align:center"><?= $year1 . '年' . $month1 . '月 ' ?></h1>
    <table class="layui-table">
        <thead>
            <tr>
                <th>序号</th>
                <th>项目负责人</th>
                <th>项目名称</th>
                <th>添加时间</th>
                <th>已结余费用</th>
                <th>总费用</th>
                <th>结余情况</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($p_list as $k => $p) : ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['r_name'] ?></td>
                    <td><?= $p['p_name'] ?></td>
                    <td><?= $p['add_time'] ?></td>
                    <td><?= $p['p_money'] ?></td>
                    <td><?= $p['t_money'] ?></td>
                    <td>
                        <?php if ($p['is_ok'] == 0) {
                            echo '已结余';
                        } elseif ($p['is_ok'] == 1) {
                            echo '部分结余';
                        } else {
                            echo '未结余';
                        } ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
        </div>
</body>

</html>