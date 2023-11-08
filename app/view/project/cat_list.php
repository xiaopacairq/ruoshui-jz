<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>项目类型表</title>
    <!-- 引入layui组件库 -->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
</head>
<style>
h2 {
    text-align: center;
    margin: 10px 0;
}

.layui-form {
    padding: 10px;
}

.box {
    padding: 10px;

    display: flex;
    flex-flow: row wrap;
}

.box-item {
    margin: 5px;
}
</style>

<body>
    <h2>项目类型添加</h2>
    <div class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="c_name" placeholder="项目类型" class="layui-input" id="test-form-input">
            </div>
            <div class="layui-input-inline" style="width: 20px;">
                <i class="layui-icon layui-icon-read" id="icon" style="padding-right:2px;font-size:25px;"></i>
            </div>
            <div class="layui-inline">
                <div id="colorpicker"></div>
            </div>
            <div class="layui-inline">
                <button type="button" class="layui-btn" onclick="save()">添加</button>
            </div>
        </div>
    </div>
    <div class="box">
        <?php foreach ($project_c_list as $c) : ?>
        <?php if ($c['cid'] > 1) : ?>
        <div class="box-item">
            <i class="layui-icon layui-icon-read"
                style="padding-right:2px;color:<?= $c['icon_color'] ?> ;font-size:20px;"></i>
            <span><?= $c['c_name'] ?></span>
            <a href="#" onclick="del(<?= $c['cid'] ?>)"><span class="layui-badge"
                    style="background-color:white;color:black">X</span></a>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <script>
    var $ = layui.jquery;
    var colorpicker = layui.colorpicker;

    var icon_color = ''; //默认图标颜色为空
    // 颜色选择框
    colorpicker.render({
        elem: '#colorpicker',
        done: function(color) {
            $('#icon').css('color', color);
            icon_color = color
            // console.log(icon_color);

        }
    });

    // 添加项目类型
    function save() {
        var c_name = $('input[name="c_name"]').val(); //项目名称
        icon_color = icon_color //图标样式

        if (c_name == '' || icon_color == '') {
            layer.msg('必填项不能为空', {
                icon: 2
            })
        } else {
            $.post('/project/cat_add_save', {
                c_name,
                icon_color
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
                        window.location.reload()
                    }, 1000);
                }
            }, 'json');
        }
    }

    // 删除项目类型
    function del(cid) {

        layer.confirm('确定要删除该数据吗？', {
            btn: ['确认', '取消'] //按钮
        }, function() {
            $.post('/project/cat_del', {
                cid
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
                        window.location.reload()
                    }, 1000);
                }
            }, 'json');
        });

    }
    </script>
</body>

</html>