<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:59:"D:\objects\yingjing/application/index\view\login\login.html";i:1496280380;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/static/css/public.css"/>
    <style  type="text/css">
        .mask{
            position: absolute;
            width:2.4rem;
            height:2.4rem;
            background: #fff;
            opacity: 0.8;
        }
        .hide{
            display: none;
        }
        .MaskContent{
            position: absolute;
            width:2rem;
            height:2rem;
            color: red;
            text-align: center;
            font-size: 0.25rem;
            z-index: 100;
            padding-top: 50px;
        }
    </style>
</head>

<body>
<div class="login_box">
    <div class="login">
        <h1>这是登录页面</h1>
        <form class="login_form" style="position: relative">
            <div class="input">
                <input type="text" name="" maxlength="11" id="username" value="" placeholder="用户名" />
                <img class="login_icon" src="__PUBLIC__/static/images/icon_user.png"/>
            </div>
            <div class="input">
                <input type="password" name="" id="password" value="" placeholder="密　码" />
                <img class="login_icon" src="__PUBLIC__/static/images/icon_password.png"/>
            </div>
            <div class="input">
                <input class="validation_input" type="text" name="" id="verify" value="" placeholder="验证码" />
                <img class="login_icon" src="__PUBLIC__/static/images/icon_validation.png"/>
                <img class="validation" src="<?php echo url('index/login/verify_cap'); ?>"   id="captcha" onclick="this.src='/index/login/verify_cap?d='+Math.random();" title="点击刷新" alt="captcha" />
            </div>
            <button type="button" id="login_submit">登录</button>
        </form>
    </div>
</div>
</body>
<script src="__PUBLIC__/static/js/jquery.js"></script>
<script src="__PUBLIC__/layer/layer.js"></script>
<script>
    $(function(){
        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                $("#login_submit").click();
            }
        };
        /*用户名登录*/
        $("#login_submit").click(function () {
            if ($("#username").val().length == 0) {
                layer.msg("用户名不能为空", {time: 3000, icon: 7});
                return;
            }
            if ($("#password").val().length == 0) {
                layer.msg("密码不能为空", {time: 3000, icon: 7});
                return;
            }
            if ($("#verify").val().length == 0) {
                layer.msg("验证码不能为空", {time: 3000, icon: 7});
                return;
            }
            $.ajax({
                url: "/index/login/login_data",
                type: 'post',
                dataType: 'json',
                data: {
                    username: $("#username").val(),
                    password: $("#password").val(),
                    verify: $("#verify").val()
                },
                success: function (data) {
                    console.log(data);
                    if(data.code == 1){
                        layer.msg(data.msg,{time:3000,icon:6});
                        setInterval(function () {
                            window.location.href='/index/index/index';
                        },3000);
                    }else{
                        layer.msg(data.msg,{time:3000,icon:5});
                        $('#captcha').click();
                    }
                },
                error:function(){
                    layer.msg("网络出错，请稍后再试！",{time:3000,icon:6});
                    $('#captcha').click();
                }
            });
        });
        /*var time = window.setInterval(function () {window.location.reload();},35000);
        //系统定时检测扫码登录
        var Timer = window.setInterval(function(){
            $.ajax({
                type:"POST",
                url:"/bigdata/login/queryQrcode",
                async:true,
                success:function(data){
                    console.log(data);
                    if(data==-2){
                        window.clearInterval(Timer);
                        $('.mask').removeClass('hide');
                        $('.MaskContent').removeClass('hide')
                    }
                    if(data == -1){
                        layer.alert('登录失败！',{time:3000,icon:5});
                    }
                    if(data == 2 ){
                        window.clearInterval(Timer);
                        layer.confirm('对不起！您还没有登录大屏端管理的权限，请联系管理员',{
                            btn:["确定","取消"]
                        }, function () {
                            window.location.reload();
                        }, function () {
                            window.location.reload();
                        });
                    }
                    if(data==1){
                        layer.msg("登录成功",{time:3000,icon:1});
                        window.location.href='/bigdata/index/index';
                        window.setTimeout(function(){
                            $.ajax({
                                type:"POST",
                                url:"/bigdata/login/queryQrcode"
                            })
                        })
                    }
                }
            });
        },2000);*/
        //忘记密码

//			点击二维码蒙版刷新
        $('.MaskContent').click(function(){
            location.reload();
        })
    });
</script>
</html>
