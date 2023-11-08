<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学习会经费明细添加</title>
</head>
<!-- 引入layui组件库 -->
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/layui/layui.js"></script>

<body style="padding: 20px;">
    <div class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">进账时间</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" id="add_time" name="add_time" required lay-verify="required"
                    autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">@ 登记人</label>
            <div class="layui-input-block">
                <input type="text" name="u_name" required lay-verify="required" autocomplete="off" placeholder="格式：张XX"
                    class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"># 记账金额</label>
            <div class="layui-input-block">
                <input type="text" id="u_money" name="u_money" placeholder="格式：199.99" class="layui-input" required
                    lay-verify="required" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">Aa 信息备注</label>
            <div class="layui-input-block">
                <textarea name="remark" placeholder="请输入30字以内的信息备注" class="layui-textarea" required
                    lay-verify="required" autocomplete="off"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
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

    // 保存数据
    function save() {
        var add_time = $('input[name="add_time"]').val();
        var u_name = $('input[name="u_name"]').val();
        var u_money = $('input[name="u_money"]').val();
        var remark = $('textarea[name="remark"]').val();
        if (add_time == '' || u_name == '' || u_money == '' || remark == '') {
            layer.msg('必填项不能为空', {
                icon: 2
            })
        } else {
            $.post('/project/project_cost_add_save', {
                add_time,
                u_name,
                u_money,
                remark
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
    $("#u_money").numbox({});
    </script>
</body>

</html>