<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{:config('app_name')}</title>
    {include file="common/require"}
    <style>
        .user-box{
            border-radius:5px;
            box-shadow: 0 0 6px 0 rgba(0, 0, 0, .05);
            margin-top:50px;
        }
        .user-zhebi{
            width:100%;
            height:100vh;
            position: fixed;
            top: 0;
            z-index: -99;
            background: white;
            opacity: 0;
            transition:  .1s;
        }
        .user-zhebi-appear{
            z-index: 99;
            opacity: 1;
        }
        .user-box-info{
            background-image: url("https://s1.ax1x.com/2020/06/14/tzsjDP.jpg");
            background-size: 100% 100%;
            width: 100%;
            height:200px;
            padding-top:10px;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
        }
        .user-box-info-img{
            width:100px;
            height: 100px;
            line-height:100px;
            text-align: center;
            position: relative;
            border:2px solid hsla(0,0%,100%,.4);
            box-shadow: 0 0 6px 0 rgba(0, 0, 0, .05);
            border-radius: 100%;
            overflow: hidden;
            margin: 0 auto;
        }
        .user-box-info-img img{
            width:100%;
            height:100%;
        }
        .user-box-info-img-hidden{
            width:100%;
            height:100%;
            background: rgba(0,0,0,.5);
            text-align: center;
            color:white;
            border-radius: 100%;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity .3s;
            cursor:pointer;
        }
        .user-box-info-img:hover .user-box-info-img-hidden{
            opacity: 1;
        }
        .user-box-username{
            padding: 15px;
            color:white;
            font-size:18px;
            text-align: center;
            font-weight: bold;
        }
        .user-box-qianming{
            width:100%;
            height:50px;
            text-align: center;
        }
        .user-box-qianming input{
            width:70%;
            height:50%;
            text-align: center;
            font-size:15px;
            background: none;
            border:none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            transition: all .3s;
        }
        .user-box-qianming input::-webkit-input-placeholder {
            color: white;
            font-weight: normal;
            font-size:15px;
            letter-spacing: 1px;
        }
        .user-box-qianming input:hover{
            background: hsla(0,0%,100%,.2);
            box-shadow: 0 0 0 1px hsla(0,0%,100%,.5);
        }
        .user-box-qianming input:focus{
            background: white;
            color: black;
        }
        .user-box-detailed{
            width: 100%;
            height: 100%;
            margin-top:10px;
            border: 1px solid #e1e2e5;
            position: relative;
            border-radius: 5px;
            overflow: hidden;
        }
        .user-box-detailed-left{
            width: 20%;
            height: 100%;
            position: absolute;
            border-right: 1px solid #e1e2e5;
            background: rgb(250,250,250);
        }
        .user-box-detailed-left-small{
            width: 100%;
            height:50px;
            text-align: center;
            line-height: 50px;
            color:#99a2aa;
            cursor:pointer;
        }
        .user-box-detailed-left-small:last-child{
            border-bottom: 1px solid #e1e2e5;
        }
        .user-box-detailed-left-small:hover{
            background: #e1e4ea;
        }
        .user-box-detailed-left-small-click{
            background:#e33e33;
            color:white;
        }
        .user-box-detailed-left-small-click:hover{
            background:#e33e33;
            color:white;
        }
        .user-box-detailed-right{
            padding-left: 20%;
            width: 100%;
        }
        .user-box-detailed-left-geren{
            width: 100%;
            height:50px;
            text-align: center;
            line-height: 50px;
            color:#99a2aa;
            border-bottom: 1px solid #e1e2e5;
        }
        .user-box-detailed-right-im{
            width: 100%;
            height:50px;
            line-height: 50px;
            border-bottom: 1px solid #e1e2e5;
        }
        .user-box-detailed-right-im span{
            margin-left:30px;
            padding-left:10px;
            color:#e33e33;
            border-left:3px solid #e33e33;
        }
        .user-box-detailed-right-info{
            width:100%;
        }
        .user-box-detailed-right-info-small{
            width: 100%;
            height:auto;
            padding-top:30px;
        }
        .user-box-detailed-right-info-small-text{
            width: 20%;
            height: 30px;
            line-height: 30px;
            float: left;
            text-align: right;
        }
        .user-box-detailed-right-info-small-input{
            width: 70%;
            float: left;
        }
        .user-box-detailed-right-info-small-input input[type="text"]{
            width:50%;
            height:30px;
            border:1px solid lightgray;
            padding:5px;
            border-radius: 5px;
            transition: .2s;
        }
        .user-box-detailed-right-info-small-input input[type="text"]:hover,.user-box-detailed-right-info-small-input textarea:hover{
            border-color: #8391a5;
        }
        .user-box-detailed-right-info-small-input input[type="text"]:focus,.user-box-detailed-right-info-small-input textarea:focus{
            border-color: #e33e33;
        }
        .user-box-detailed-right-info-small-input textarea{
            width:80%;
            height:60px;
            border:1px solid lightgray;
            resize: none;
            font-size: 18px;
            border-radius: 5px;
            padding:5px;
            transition: .2s;
        }
        .user-box-detailed-right-info-small-input-span{
            display: block;
            width:30px;
            height:30px;
            line-height: 30px;
            float: left;
            text-align: center;
            border-radius: 5px;
            border:1px solid lightgray;
            margin-left:5px;
            background: white;
            cursor:pointer;
        }
        .user-box-detailed-right-info-small-input-click{
            color:white;
            background: #e33e33;
        }
        .user-box-detailed-right-info-small-hr{
            width:80%;
            border-top:1px solid lightgray;
            margin: 0 auto;
        }
        .user-box-detailed-right-info-small-btn{
            width:20%;
            height:40px;
            background: #e33e33;
            color:white;
            text-align: center;
            line-height: 40px;
            margin:0 auto;
            border-radius: 5px;
            cursor:pointer;
        }
        .user-box-detailed-right-info-small:last-child{
            padding-bottom: 200px;
        }
    </style>
    <script src="__STATIC__/libs/laydate/laydate.js"></script>
</head>
<body>
    {:widget('common/header')}
    <!--白色遮蔽层-->
    <div class="user-zhebi"></div>
    <!--白色遮蔽层-->

    <div class="wrap user-box">
        <div class="user-box-info">
            <div class="user-box-info-img">
                <div class="user-box-info-img-hidden">
                    更换头像
                </div>
                <img src="https://dss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1906469856,4113625838&fm=26&gp=0.jpg" alt="">
            </div>
            <div class="user-box-username">
                {$userInfo.nick_name}
            </div>
            <div class="user-box-qianming">
                <input type="text" placeholder="编辑个性签名" value="{$userInfo.user_autograph}">
            </div>
        </div>

        <div class="user-box-detailed clearfix">
            <div class="user-box-detailed-left">
                <div class="user-box-detailed-left-geren">
                    个人中心
                </div>
                <div class="user-box-detailed-left-small user-box-detailed-left-small-click">
                    <i class="fa fa-user-o" aria-hidden="true"></i>
                    <span>我的信息</span>
                </div>
                <div class="user-box-detailed-left-small">
                    <i class="fa fa-user-o" aria-hidden="true"></i>
                    <span>啦啦啦啦</span>
                </div>
            </div>
            <div class="user-box-detailed-right">
                <div class="user-box-detailed-right-im">
                    <span id="user-title">我的信息</span>
                </div>
                <!--<div class="user-box-detailed-right-info">
                    <div class="user-box-detailed-right-info-small clearfix">
                        <div class="user-box-detailed-right-info-small-text">
                            <span>昵称：</span>
                        </div>
                        <div class="user-box-detailed-right-info-small-input">
                            <input type="text">
                        </div>
                    </div>

                    <div class="user-box-detailed-right-info-small clearfix">
                        <div class="user-box-detailed-right-info-small-text ">
                            <span>账号：</span>
                        </div>
                        <div class="user-box-detailed-right-info-small-input">
                            <input type="text">
                        </div>
                    </div>

                    <div class="user-box-detailed-right-info-small clearfix">
                        <div class="user-box-detailed-right-info-small-text">
                            <span>个性签名：</span>
                        </div>
                        <div class="user-box-detailed-right-info-small-input">
                            <textarea name="qianming"></textarea>
                        </div>
                    </div>

                    <div class="user-box-detailed-right-info-small clearfix">
                        <div class="user-box-detailed-right-info-small-text">
                            <span>性别：</span>
                        </div>
                        <div class="user-box-detailed-right-info-small-input clearfix">
                            <span class="user-box-detailed-right-info-small-input-span user-box-detailed-right-info-small-input-click">男</span>
                            <span class="user-box-detailed-right-info-small-input-span">女</span>
                            <span class="user-box-detailed-right-info-small-input-span">保密</span>
                        </div>
                    </div>

                    <div class="user-box-detailed-right-info-small clearfix">
                        <div class="user-box-detailed-right-info-small-text">
                            <span>出生日期：</span>
                        </div>
                        <div class="user-box-detailed-right-info-small-input clearfix">
                            <input class="start" type="text" name="datetime" placeholder="请选择时间">
                        </div>
                    </div>

                    <div class="user-box-detailed-right-info-small">
                        <div class="user-box-detailed-right-info-small-hr"></div>
                    </div>

                    <div class="user-box-detailed-right-info-small">
                        <div class="user-box-detailed-right-info-small-btn">保存</div>
                    </div>

                </div>-->
                {include file="user/info"}
            </div>
        </div>
    </div>
</body>
<script>
    //选项卡下标
    var nowIndex = 0;
    //性别下标
    var sexNum = 0;
    //切换选项卡
    $(".user-box-detailed-left-small").click(function(){
        var index = $(".user-box-detailed-left-small").index(this);
        if(nowIndex == index){
            return;
        }
        nowIndex = index;
        $(".user-box-detailed-left-small").removeClass("user-box-detailed-left-small-click");
        $(".user-box-detailed-left-small").eq(index).addClass("user-box-detailed-left-small-click");
        $(".user-zhebi").addClass("user-zhebi-appear");
        setTimeout(function(){$(".user-zhebi").removeClass("user-zhebi-appear");},150);
        var text = $("span",$(".user-box-detailed-left-small").eq(index)).html();
        $("#user-title").html(text);
    });
    //性别切换
    $(".user-box-detailed-right-info-small-input-span").click(function(){
        var index = $(".user-box-detailed-right-info-small-input-span").index(this);
        sexNum = index;
        $(".user-box-detailed-right-info-small-input-span").removeClass("user-box-detailed-right-info-small-input-click");
        $(".user-box-detailed-right-info-small-input-span").eq(index).addClass("user-box-detailed-right-info-small-input-click");
    });

    //个性签名摁下互回车
    $("input",$(".user-box-qianming")).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $("input",$(".user-box-qianming")).blur();
        }
    });
    //时间控件
    laydate.render({
        elem: '.start',
        theme: '#e33e33',
        max:0
    });

    $("#sub_btn").click(function(){
        var obj = new Object();
        obj["nickname"] = $("input[name='nick_name']",$(".user-box-detailed-right-info-small-input")).val();
        obj["username"] = $("input[name='user_name']",$(".user-box-detailed-right-info-small-input")).val();
        obj["qianming"] = $("textarea",$(".user-box-detailed-right-info-small-input")).val();
        obj["birthday"] = $("input[name='birthday']",$(".user-box-detailed-right-info-small-input")).val();
        obj["usersex"] = sexNum;
        //console.log(obj);
        $.ajax({
            type:"POST",
            url:"{:url('index/User/editUser')}",
            data:obj,
            headers: {
                token: $.cookie('im_token'),
            },
            dataType:"json",
            success:function(data){
                console.log(data);
            }
        });
    });



</script>
</html>