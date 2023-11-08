<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>分类数据表</title>
    <!-- 引入layui组件库 -->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
</head>

<body>
    <h1 style="padding: 10px;text-align:center"><?= $year1 . '年' . $month1 . '月 ' . $c_name ?></h1>
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
            <?php foreach ($c_list as $c) : ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= $c['r_name'] ?></td>
                <td><?= $c['p_name'] ?></td>
                <td><?= $c['add_time'] ?></td>
                <td><?= $c['p_money'] ?></td>
                <td><?= $c['t_money'] ?></td>
                <td>
                    <?php if ($c['is_ok'] == 0) {
                            echo '已结余';
                        } elseif ($c['is_ok'] == 1) {
                            echo '部分结余';
                        } else {
                            echo '未结余';
                        } ?>
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</body>

</html>