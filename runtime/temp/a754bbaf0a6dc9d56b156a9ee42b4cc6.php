<?php /*a:1:{s:78:"D:\phpstudy_pro\WWW\ruoshui-jz-linux.cn\app\view\project\project_cost_list.php";i:1675574846;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学习会经费明细表</title>
    <!-- 引入layui组件库 -->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
    <!-- 设置网站图标 -->
    <link rel="shortcut icon" href="/static/images/costt.ico" type="image/x-icon">
    <link rel="stylesheet" href="/static/css/project_cost.css">
</head>

<body>
    <div class="head">
        <div class="left">
            <a href="/project/index"><img src="/static/images/index.svg" alt="">若水学习会财务总表</a>
            <span>/</span>
            <a href="/project/project_cost_list"><img src="/static/images/cost.svg" alt="">学习会经费明细表</a>
        </div>
        <div class="right"><i class="layui-icon layui-icon-user"></i> 用户名：<?= $uname ?></div>
    </div>
    <div class="title">
        <img src="/static/images/cost.svg" alt=""><span>学习会经费明细表</span>
    </div>

    <!-- 动态渲染表格 -->
    <table class="layui-hide" id="project_cost" lay-filter="test"></table>

    <!-- 表格上部工具栏 -->
    <script type="text/html" id="toolbarDemo">
    <div class="layui-form">
        <div class="layui-form-item">
            <!-- 检索备注 -->
            <div class="layui-inline">
                <input type="text" name="search_remark" placeholder="检索备注" autocomplete="off" class="layui-input">
            </div>
            <!-- 检索登记人 -->
            <div class="layui-inline">
                <input type="text" name="search_u_name" placeholder="检索登记人" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-inline">
                <div class="layui-inline" id="range-time">
                    <div class="layui-input-inline">
                        <input type="text" autocomplete="off" id="test-startDate-1" class="layui-input"
                            placeholder="开始日期">
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" autocomplete="off" id="test-endDate-1" class="layui-input"
                            placeholder="结束日期">
                    </div>
                </div>
            </div>
            <!-- 搜索按钮 -->
            <div class="layui-inline">
                <button class="layui-btn layui-btn-normal" onclick="search()"><i class="layui-icon layui-icon-search"
                        style="font-size:24px"></i></button>
            </div>
            <!-- 返回按钮 -->
            <div class="layui-inline">
                <button class="layui-btn layui-btn-primary layui-border-black" onclick="setTimeout(function() {
                                    window.location.reload();
                                }, 1000);"><i class="layui-icon layui-icon-refresh"
                        style="font-size:24px"></i></button>
            </div>
            <!-- 添加数据框 -->
            <div class="layui-inline">
                <button class="layui-btn layui-btn-primary layui-border-black" onclick="add()"><i
                        class="layui-icon layui-icon-add-1" style="font-size:30px"></i></button>
            </div>
            <!-- 剩余金额 -->
            <div class="layui-inline">
                <button class="layui-btn layui-btn-disabled">
                    <?php if ($t_money < 0) : ?>
                    <span style="color:crimson;">结余金额：￥<?= $t_money ?></span>
                    <?php else : ?>
                    <span style="color:green;">结余金额：￥<?= $t_money ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </div>
    </script>

    <script>
    var table = layui.table; // 表格组件
    var layer = layui.layer; // 开启layer弹窗组件
    var laydate = layui.laydate; // 日期组件
    var util = layui.util; // 开启固定块
    var $ = layui.jquery;

    // 默认定义数据
    var id = ''; // 更新的id，默认为空，表示没有选中修改数据

    // 检索数据定义
    var start_time = ''; // 开始时间
    var end_time = ''; // 结束时间
    var search_u_name = ''; // 筛选内容
    var search_remark = ''; // 筛选内容


    // 动态渲染表格
    table.render({
        title: '若水学习会记账明细总表' + new Date().toLocaleDateString(),
        elem: '#project_cost',
        url: '/project/project_cost_data',
        toolbar: '#toolbarDemo',
        skin: 'line',
        defaultToolbar: ['print', 'exports'],
        page: false,
        method: 'post',
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
                field: 'remark',
                width: 550,
                minWidth: 550,
                title: 'Aa 信息备注',
            }, {
                field: 'add_time',
                width: 180,
                minWidth: 180,
                title: '进账时间',
                sort: true,
                align: 'center'
            }, {
                field: 'u_money',
                width: 180,
                minWidth: 180,
                title: '# 记账金额',
                sort: true,
                align: 'right',
                templet: function(d) {
                    if (d.u_money >= 0) {
                        return '<span style="color:#5FB878">CN￥' + d.u_money + '</span>'
                    } else {
                        return '<span style="color:#FFB800">CN￥' + d.u_money +
                            '</span>';
                    }

                }
            }, {
                field: 'u_name',
                title: '@ 登记人',
                width: 150,
                minWidth: 150,
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

    //触发行双击事件，弹出详细信息
    table.on('rowDouble(test)', function(obj) {
        // console.log(obj.tr) //得到当前行元素对象
        console.log(obj.data) //得到当前行数据
        var id = obj.data.id;
        layer.open({
            type: 2,
            title: '经费明细详细信息',
            offset: 'r',
            maxmin: false, //开启最大化最小化按钮
            area: ['500px', '100%'],
            shadeClose: true,
            shade: 0.3,
            anim: 5,
            scrollbar: false,
            content: '/project/project_cost_detail?id=' + id

        });

    });

    // 添加
    function add() {
        layer.open({
            type: 2,
            title: '经费明细添加',
            offset: 'r',
            maxmin: false, //开启最大化最小化按钮
            area: ['400px', '100%'],
            shadeClose: false,
            shade: 0.3,
            anim: 5,
            content: '/project/project_cost_add'
        });
    }

    // 搜索
    function search() {
        search_remark = $.trim($('input[name="search_remark"]').val());
        search_u_name = $.trim($('input[name="search_u_name"]').val());
        // console.log(start_time);
        // console.log(end_time);
        // console.log(search_remark);
        // console.log(search_u_name);
        table.render({
            title: '若水学习会记账明细总表' + new Date().toLocaleDateString(),
            elem: '#project_cost',
            url: '/project/project_cost_data',
            where: {
                start_time,
                end_time,
                search_remark,
                search_u_name
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
                    field: 'remark',
                    width: 550,
                    minWidth: 550,
                    title: 'Aa 信息备注',
                }, {
                    field: 'add_time',
                    width: 180,
                    minWidth: 180,
                    title: '进账时间',
                    sort: true,
                    align: 'center'
                }, {
                    field: 'u_money',
                    width: 180,
                    minWidth: 180,
                    title: '# 记账金额',
                    sort: true,
                    align: 'right',
                    templet: function(d) {
                        return '<span>CN￥' + d.u_money + '</span>';
                    }
                }, {
                    field: 'u_name',
                    title: '@ 登记人',
                    width: 150,
                    minWidth: 150,
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
        $.get('/project/project_cost_data', {
            start_time,
            end_time,
            search_remark,
            search_u_name
        }, function(res) {
            console.log(res.search.search_remark);
            $('input[name="search_remark"]').val(res.search.search_remark);
            $('input[name="search_u_name"]').val(res.search.search_u_name);
            $('input[id="test-startDate-1"]').val(res.search.start_time);
            $('input[id="test-endDate-1"]').val(res.search.end_time);
        }, 'json')
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
                        area: ['400px', '100%'],
                        shadeClose: false,
                        shade: 0.3,
                        anim: 5,
                        title: '经费明细修改',
                        content: '/project/project_cost_edit?id=' + id
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
                        $.get('/project/project_cost_del', {
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
    </script>

</body>

</html>