<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学习会项目添加</title>
</head>
<!-- 引入layui组件库 -->
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/layui/layui.js"></script>
<style>
    .layui-form-label {
        width: 120px;
    }
</style>

<body style="padding: 20px;">
    <div class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">项目登记时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="add_time" name="add_time" required lay-verify="required" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">@ 项目负责人</label>
            <div class="layui-input-inline">
                <input type="text" name="r_name" required lay-verify="required" autocomplete="off" placeholder="格式：张XX" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"># 项目类型</label>
            <div class="layui-input-inline">
                <select name="cid" lay-verify="required">
                    <option value="1">项目类型</option>
                    <?php foreach ($project_c_list as  $project_c) : ?>
                        <option value=<?= $project_c['cid'] ?>>
                            <?= $project_c['c_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- 添加项目类型 -->
            <div class="layui-input-inline">
                <button class="layui-btn layui-btn-primary layui-btn-sm" onclick="add_cat()">
                    <i class="layui-icon">&#xe654;</i>
                </button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Aa 项目名称</label>
            <div class="layui-input-inline">
                <input type="text" name="p_name" required lay-verify="required" autocomplete="off" placeholder="项目名称" class=" layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"># 已结余费用</label>
            <div class="layui-input-inline">
                <input type="text" id="p_money" name="p_money" placeholder="格式：199.99" class="layui-input" required lay-verify="required" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"># 总费用</label>
            <div class="layui-input-inline">
                <input type="text" id="t_money" name="t_money" placeholder="格式：199.99" class="layui-input" required lay-verify="required" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结余情况</label>
            <div class="layui-input-block">
                <input type="radio" name="is_ok" value="0" title="已结余" checked>
                <input type="radio" name="is_ok" value="1" title="部分结余">
                <input type="radio" name="is_ok" value="2" title="未结余">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline">
                <button class="layui-btn" onclick="save()">保存</button>
            </div>
        </div>
    </div>
    <script>
        var laydate = layui.laydate; //开启日期组件
        var $ = layui.jquery;
        var layer = layui.layer;
        //执行一个laydate实例

        laydate.render({
            elem: '#add_time',
            value: new Date(),
        });

        // 添加分类
        function add_cat() {
            layer.open({
                type: 2,
                title: '项目类型表',
                skin: 'layui-layer-lan',
                maxmin: false, //开启最大化最小化按钮
                area: ['450px', '80%'],
                shadeClose: false,
                shade: 0.3,
                anim: 5,
                content: '/project/cat_list',
                cancel: function() {
                    setTimeout(function() {
                        window.location.reload()
                    }, 1000);
                }
            });
        }

        // 保存数据
        function save() {
            var add_time = $('input[name="add_time"]').val();
            var r_name = $('input[name="r_name"]').val();
            var cid = $('select[name="cid"]').val();
            var p_name = $('input[name="p_name"]').val();
            var p_money = $('input[name="p_money"]').val();
            var t_money = $('input[name="t_money"]').val();
            var is_ok = $('input[name="is_ok"]').val();
            if (add_time == '' || r_name == '' || cid == '' || p_name == '' || p_money == '' || t_money == '' || is_ok ==
                '') {
                layer.msg('必填项不能为空', {
                    icon: 2
                })
            } else if (p_money > t_money) {
                layer.msg('金额输入有误', {
                    icon: 2
                })
            } else {
                $.post('/project/project_total_add_save', {
                    add_time,
                    r_name,
                    cid,
                    p_name,
                    p_money,
                    t_money,
                    is_ok
                }, function(res) {
                    if (res.code > 0) {
                        layer.msg(res.msg, {
                            icon: 2
                        })
                    } else {
                        layer.msg(res.msg, {
                            icon: 1
                        })
                        setTimeout(function() {
                            parent.window.location.reload()
                        }, 1000);
                    }
                }, 'json');
            }
        }

        // 金额输入框校验
        (function($) {
            // 数值输入框
            $.fn.numbox = function(options) {
                var type = (typeof options);
                if (type == 'object') {
                    // 创建numbox对象
                    if (options.width) this.width(options.width);
                    if (options.height) this.height(options.height);
                    this.bind("input propertychange", function(obj) {
                        numbox_propertychange(obj.target);
                    });
                    this.bind("change", function(obj) {
                        var onChange = options.onChange;
                        if (!onChange) return;
                        var numValue = Number(obj.target.value);
                        onChange(numValue);
                    });
                    this.bind("hide", function(obj) {
                        var onHide = options.onHide;
                        if (!onHide) return;
                        var numValue = Number(obj.target.value);
                        onHide(numValue);
                    });
                    return this;
                } else if (type == 'string') {
                    // type为字符串类型，代表调用numbox对象中的方法
                    var method = eval(options);
                    if (method) return method(this, arguments);
                }
            }
            // 属性值变化事件
            function numbox_propertychange(numbox) {
                if (numbox.value == '-' || numbox.value == numbox.oldvalue) return;
                var numvalue = Number(numbox.value);
                if (isNaN(numvalue)) {
                    numbox.value = numbox.oldvalue;
                } else {
                    numbox.oldvalue = numbox.value;
                }
            }
            // 获取值
            function getValue(numbox) {
                var value = numbox.val();
                return Number(value);
            }
            // 设置值
            function setValue(numbox, params) {
                if (params[1] == undefined) return;
                var numvalue = Number(params[1]);
                if (!isNaN(numvalue)) {
                    for (var i = 0; i < numbox.length; i++) {
                        numbox[i].focus();
                        numbox[i].value = numvalue;
                        numbox[i].oldvalue = numvalue;
                    }
                }
            }
        })($);

        // 开启金额控制组件
        $("#p_money").numbox({});
        $("#t_money").numbox({});
    </script>
</body>

</html>