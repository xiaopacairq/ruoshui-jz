<?php /*a:1:{s:79:"D:\phpstudy_pro\WWW\ruoshui-jz-linux.cn\app\view\project\project_total_list.php";i:1675574846;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($project_c_list['cid'])) ? $project_c_list['c_name'] : '学习会项目情况总表'  ?></title>
    <!-- 引入layui组件库 -->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
    <!-- 设置网站图标 -->
    <link rel="shortcut icon" href="/static/images/project_total.ico" type="image/x-icon">
    <link rel="stylesheet" href="/static/css/project_total.css">
</head>

<body>
    <?php if (isset($project_c_list['cid'])) : ?>
        <input type="hidden" name="cid" value="<?= $project_c_list['cid'] ?>">
    <?php endif; ?>
    <div class="head">
        <div class="left">
            <a href="/project/index"><img src="/static/images/index.svg" alt="">若水学习会财务总表</a>
            <span>/</span>
            <?php if (isset($project_c_list['cid'])) : ?>
                <a href="/project/project_total_list">
                    <i class="layui-icon layui-icon-read" style="padding:10px;font-size:20px;color:<?= $project_c_list['icon_color'] ?>"></i><?= $project_c_list['c_name'] ?>
                </a>
            <?php else : ?>
                <a href="/project/project_total_list">
                    <img src="/static/images/project_total.svg" alt="">学习会项目情况总表
                </a>
            <?php endif; ?>
        </div>
        <div class="right"><i class="layui-icon layui-icon-user"></i> 用户名：<?= $uname ?></div>
    </div>

    <div class="title">
        <?php if (isset($project_c_list['cid'])) : ?>
            <i class="layui-icon layui-icon-read" style="padding:10px;font-size:50px;color:<?= $project_c_list['icon_color'] ?>"></i><span><?= $project_c_list['c_name'] ?></span>
        <?php else : ?>
            <img src="/static/images/project_total.svg" alt=""><span>学习会项目情况总表</span>
        <?php endif; ?>
    </div>

    <!-- 动态渲染表格 -->
    <table class="layui-hide" id="project_total" lay-filter="test"></table>

    <?php if (!isset($project_c_list['cid'])) : ?>
        <!-- 表格上部工具栏 -->
        <script type="text/html" id="toolbarDemo">
            <div class="layui-form">
                <div class="layui-form-item">
                    <!-- 检索项目负责人 -->
                    <div class="layui-inline">
                        <input type="text" name="search_r_name" placeholder="检索项目负责人" autocomplete="off" class="layui-input">
                    </div>
                    <!-- 检索项目名称 -->
                    <div class="layui-inline">
                        <input type="text" name="search_p_name" placeholder="检索项目名称" autocomplete="off" class="layui-input">
                    </div>
                    <!-- 时间范围 -->
                    <div class="layui-inline">
                        <div class="layui-inline" id="range-time">
                            <div class="layui-input-inline">
                                <input type="text" autocomplete="off" id="test-startDate-1" class="layui-input" placeholder="开始日期">
                            </div>
                            <div class="layui-form-mid">-</div>
                            <div class="layui-input-inline">
                                <input type="text" autocomplete="off" id="test-endDate-1" class="layui-input" placeholder="结束日期">
                            </div>
                        </div>
                    </div>
                    <!-- 搜索按钮 -->
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-normal" onclick="search()"><i class="layui-icon layui-icon-search" style="font-size:24px"></i></button>
                    </div>
                    <!-- 返回按钮 -->
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-primary layui-border-black" onclick="setTimeout(function() {
                                    window.location.reload();
                                }, 1000);"><i class="layui-icon layui-icon-refresh" style="font-size:24px"></i></button>
                    </div>
                    <!-- 添加数据框 -->
                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-primary layui-border-black" onclick="add()"><i class="layui-icon layui-icon-add-1" style="font-size:30px"></i></button>
                    </div>

                </div>
            </div>
        </script>
    <?php endif; ?>
    <script>
        var table = layui.table; // 表格组件
        var form = layui.form; // 表单组件
        var layer = layui.layer; // 开启layer弹窗组件
        var laydate = layui.laydate; // 日期组件
        var util = layui.util; // 开启固定块
        var $ = layui.jquery;

        // 默认定义数据
        var id = ''; // 更新的id，默认为空，表示没有选中修改数据

        // 检索的数据
        var search_cid = ''; // 分类cid，默认为空
        var start_time = ''; // 开始时间
        var end_time = ''; // 结束时间
        var search_p_name = ''; // 项目名称筛选内容
        var search_r_name = ''; // 负责人筛选内容

        // 分表的标识
        var cid = $('input[name="cid"]').val();

        // 动态渲染表格
        table.render({

            title: "<?= (isset($project_c_list['cid'])) ?  $project_c_list['c_name'] : '学习会项目情况总表' ?>" + new Date()
                .toLocaleDateString(),
            elem: '#project_total',
            url: "/project/project_total_data<?= (isset($project_c_list['cid'])) ? '?cid=' . $project_c_list['cid'] : '' ?>",
            toolbar: '#toolbarDemo',
            skin: 'line',
            defaultToolbar: ['print', 'exports'],
            page: false,
            method: 'get',
            response: {
                statusName: 'code',
                statusCode: 0,
                msgName: 'msg',
                dataName: 'data'
            },
            cols: [
                [{
                    type: 'radio',
                    width: 50,
                    minWidth: 50,
                }, {
                    field: 'id',
                    width: 100,
                    minWidth: 100,
                    title: '# 序号',
                    sort: true,
                    align: 'right'
                }, {
                    field: 'r_name',
                    width: 150,
                    minWidth: 150,
                    title: '@ 项目负责人',
                }, {
                    field: 'p_name',
                    width: 300,
                    minWidth: 300,
                    title: '# 项目名称',
                }, {
                    field: 'c_name',
                    width: 180,
                    minWidth: 180,
                    title: '# 项目类型',
                    templet: function(d) {
                        return '<span style="padding:5px 10px;border-radius: 10px;background-color:' +
                            d.icon_color + '">' +
                            d.c_name + '</span>';
                    }
                }, {
                    field: 'add_time',
                    width: 150,
                    minWidth: 150,
                    title: '# 记账时间',
                    sort: true,
                    align: 'center',
                }, {
                    field: 'p_money',
                    title: '# 已结余费用',
                    width: 150,
                    minWidth: 150,
                    templet: function(d) {
                        return '<span >CN￥' + d.p_money + '</span>';
                    }
                }, {
                    field: 't_money',
                    title: '# 总费用',
                    width: 150,
                    minWidth: 150,
                    sort: true,
                    templet: function(d) {
                        return '<span >CN￥' + d.t_money + '</span>';
                    }
                }, {
                    field: 'is_ok',
                    title: '# 结算情况',
                    width: 140,
                    minWidth: 140,
                    templet: function(d) {
                        if (d.is_ok == 0) {
                            return '<span style="padding:5px 10px;border-radius: 10px;background-color:rgb(219, 237, 219);">已结余</span>';
                        }
                        if (d.is_ok == 1) {
                            return '<span style="padding:5px 10px;border-radius: 10px;background-color:rgba(227, 226, 224, 0.5);">部分结余</span>';
                        }
                        if (d.is_ok == 2) {
                            return '<span style="padding:5px 10px;border-radius: 10px;background-color:#FFB800;">未结余</span>';
                        }
                    }
                }]
            ],
        });

        // 点击单选按钮，获取数据的id，用于修改和删除
        table.on('radio(test)', function(obj) { //test 是 table 标签对应的 lay-filter 属性
            // console.log(obj); //当前行的一些常用操作集合
            // console.log(obj.checked); //当前是否选中状态
            // console.log(obj.data); //选中行的相关数据
            id = obj.data.id;
            layer.msg('请右下选择操作类型!');
        });

        //触发行双击事件，弹出详细信息
        table.on('rowDouble(test)', function(obj) {
            // console.log(obj.tr) //得到当前行元素对象
            console.log(obj.data) //得到当前行数据
            id = obj.data.id;
            layer.open({
                type: 2,
                title: '项目情况详细信息',
                offset: 'r',
                maxmin: false, //开启最大化最小化按钮
                area: ['500px', '100%'],
                shadeClose: true,
                shade: 0.3,
                anim: 5,
                scrollbar: false,
                content: '/project/project_total_detail?id=' + id
            });
        });

        // 日期组件
        laydate.render({
            elem: '#range-time',
            range: ['#test-startDate-1', '#test-endDate-1'],
            theme: '#31BDEC',
            calendar: true,
            done: function(value, s_time, e_time) {
                start_time = s_time.year + '-' + s_time.month + '-' + s_time.date;
                end_time = e_time.year + '-' + e_time.month + '-' + e_time.date;
                console.log(s_time);
                console.log(e_time);
                console.log(value);
                console.log(start_time);
                console.log(start_time);
            }
        });

        // 添加
        function add() {
            layer.open({
                type: 2,
                title: '项目明细添加',
                offset: 'r',
                maxmin: false, //开启最大化最小化按钮
                area: ['600px', '100%'],
                shadeClose: false,
                shade: 0.3,
                anim: 5,
                content: '/project/project_total_add'
            });
        }

        // 修改 删除
        //固定块 :执行修改和删除功能
        util.fixbar({
            bar1: '&#xe642',
            bar2: '&#xe640',
            css: {
                right: 50,
                bottom: 100
            },
            bgcolor: '#393D49',
            click: function(type) {
                if (type === 'bar1') {
                    if (id == '') {
                        // 当没有选中数据时
                        layer.msg('请选择一条数据！');
                    } else {
                        // 修改
                        layer.open({
                            type: 2,
                            offset: 'r',
                            maxmin: false, //开启最大化最小化按钮
                            area: ['600px', '100%'],
                            shadeClose: false,
                            shade: 0.3,
                            anim: 5,
                            title: '项目情况修改',
                            content: '/project/project_total_edit?id=' + id
                        });
                    }
                } else if (type === 'bar2') {
                    if (id == '') {
                        // 当没有选中数据时
                        layer.msg('请选择一条数据！');
                    } else {
                        // 删除
                        layer.confirm('确定要删除该数据吗？', {
                            btn: ['确认', '取消'] //按钮
                        }, function() {
                            $.get('/project/project_total_del', {
                                id
                            }, function(res) {
                                console.log(res);
                                if (res.code > 0) {
                                    layer.msg(res.msg, {
                                        icon: 2
                                    });
                                } else {
                                    layer.msg(res.msg, {
                                        icon: 1
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                }
                            }, 'json')
                        });
                    }

                }
            }
        });

        // 搜索
        function search() {
            search_p_name = $.trim($('input[name="search_p_name"]').val());
            search_r_name = $.trim($('input[name="search_r_name"]').val());
            search_cid = $.trim($('select[name="search_cid"]').val());
            // console.log(start_time);
            // console.log(end_time);
            // console.log(search_remark);
            // console.log(search_u_name);
            table.render({
                title: '学习会项目情况表' + new Date().toLocaleDateString(),
                elem: '#project_total',
                url: '/project/project_total_data',
                where: {
                    start_time,
                    end_time,
                    search_p_name,
                    search_r_name,
                    search_cid
                },
                toolbar: '#toolbarDemo',
                skin: 'line',
                defaultToolbar: ['print', 'exports'],
                page: false,
                method: 'get',
                response: {
                    statusName: 'code',
                    statusCode: 0,
                    msgName: 'msg',
                    dataName: 'data'
                },
                cols: [
                    [{
                        type: 'radio',
                        width: 50,
                        minWidth: 50,
                    }, {
                        field: 'id',
                        width: 100,
                        minWidth: 100,
                        title: '# 序号',
                        sort: true,
                        align: 'right'
                    }, {
                        field: 'r_name',
                        width: 150,
                        minWidth: 150,
                        title: '@ 项目负责人',
                    }, {
                        field: 'p_name',
                        width: 300,
                        minWidth: 300,
                        title: '# 项目名称',
                    }, {
                        field: 'c_name',
                        width: 180,
                        minWidth: 180,
                        title: '# 项目类型',
                        templet: function(d) {
                            return '<span style="padding:5px 10px;border-radius: 10px;background-color:' +
                                d.icon_color + '">' +
                                d.c_name + '</span>';
                        }
                    }, {
                        field: 'add_time',
                        width: 150,
                        minWidth: 150,
                        title: '# 记账时间',
                        sort: true,
                        align: 'center',
                    }, {
                        field: 'p_money',
                        title: '# 已结余费用',
                        width: 150,
                        minWidth: 150,
                        templet: function(d) {
                            return '<span >CN￥' + d.p_money + '</span>';
                        }
                    }, {
                        field: 't_money',
                        title: '# 总费用',
                        width: 150,
                        minWidth: 150,
                        sort: true,
                        templet: function(d) {
                            return '<span >CN￥' + d.t_money + '</span>';
                        }
                    }, {
                        field: 'is_ok',
                        title: '# 结算情况',
                        width: 140,
                        minWidth: 140,
                        templet: function(d) {
                            if (d.is_ok == 0) {
                                return '<span style="padding:5px 10px;border-radius: 10px;background-color:rgb(219, 237, 219);">已结余</span>';
                            }
                            if (d.is_ok == 1) {
                                return '<span style="padding:5px 10px;border-radius: 10px;background-color:rgba(227, 226, 224, 0.5);">部分结余</span>';
                            }
                            if (d.is_ok == 2) {
                                return '<span style="padding:5px 10px;border-radius: 10px;background-color:#FFB800;">未结余</span>';
                            }
                        }
                    }]
                ],
            });

            // 解决检索日期后 日期组件失效
            laydate.render({
                elem: '#range-time',
                range: ['#test-startDate-1', '#test-endDate-1'],
                theme: '#31BDEC',
                calendar: true,
                done: function(value, s_time, e_time) {
                    start_time = s_time.year + '-' + s_time.month + '-' + s_time.date;
                    end_time = e_time.year + '-' + e_time.month + '-' + e_time.date;
                    // console.log(s_time);
                    // console.log(e_time);
                    // console.log(value);
                    // console.log(start_time);
                    // console.log(start_time);
                }
            });

            //将搜索内容返回给检索框
            $.get('/project/project_total_data', {
                start_time,
                end_time,
                search_p_name,
                search_r_name
            }, function(res) {
                $('input[name="search_p_name"]').val(res.search.search_p_name);
                $('input[name="search_r_name"]').val(res.search.search_r_name);
                $('input[id="test-startDate-1"]').val(res.search.start_time);
                $('input[id="test-endDate-1"]').val(res.search.end_time);

            }, 'json')
        }
    </script>

</body>

</html>