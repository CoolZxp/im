<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{:config('app_name')}</title>
    {include file="common/require"}
    <style>
        .im-box {
            width: 100%;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 6px 0 rgba(0, 0, 0, .05);
        }
        .im-title {
            font-size: 26px;
        }

        .im-cate-list-item {
            float: left;
            height: 30px;
            line-height: 30px;
            padding: 0 10px;
            margin-top: 10px;
            margin-right: 10px;
            background-color: #F0F0F5;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color .5s;
        }
        .im-cate-list-item:hover {
            background-color: #E8E8EE;
        }
        .im-cate-list-item-active {
            background-color: #E33E33;
            color: #fff;
        }
        .im-room-top {
            height: 40px;
        }

        .im-room-top-title {
            float: left;
        }
        .im-room-top-new {
            float: right;
            height: 30px;
            line-height: 30px;
            padding: 0 10px;
            margin-top: 5px;
            background-color: #e33e33;
            color: #ffffff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color .5s;
        }
        .im-room-top-new:hover {
            background-color: #ea6a62;
        }
        .im-room-top-new:active {
            background-color: #de291d;
        }



        .im-room-list {
            display: flex;
            flex-wrap: wrap;
            justify-content:space-between;
        }
        .im-room-list-item {
            width: 24%;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 20px;
            cursor: pointer;
            transition: box-shadow .5s;
            box-shadow: 2px 2px 10px #ddd;
        }
        .im-room-list-item:hover {
        }
        .im-room-list-item-img {
            width: 100%;
            padding-top: 65%;
            position: relative;
        }
        .im-room-list-item-img img {
            position: absolute;
            top:0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .im-room-list-item-img-layer {
            position: absolute;
            top:0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,.4);
            opacity: 0;
            transition: opacity .5s;

            z-index: 99;
        }
        .im-room-list-item-img-layer-title {
            position: absolute;
            top: 50%;
            z-index:99;
            transform: translateY(-50%);
            width: 100%;
            color: #fff;
            text-align: center;
        }

        .im-room-list-item:hover .im-room-list-item-img-layer {
            opacity: 1;
        }
        .im-room-list-item-main {
            background-color: #f5f6f7;
            padding: 10px;
        }
        .im-room-list-item-title {
        }
        .im-room-list-item-user {
            height: 20px;
            line-height: 20px;
            color: #999;
            margin-top: 5px;
        }
        .im-room-list-item-user-info {
            float: left;
        }
        .im-room-list-item-user-face {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            vertical-align: middle;
        }
        .im-room-list-item-user-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 14px;
        }
        .im-room-list-item-user-num {
            height: 100%;
            float: right;
        }

        .im-room-no {
            width: 100%;
            text-align: center;
            margin: 10% 0;
        }

        .im-room .pagination {
            margin-top: 20px;
        }

    </style>
</head>
<body>
    {:widget('common/header')}

    <div class="wrap im">
        <div class="im-box im-cate">
            <div class="im-title">
                分类
            </div>
            <ul class="im-cate-list clearfix">
                {volist name='roomCateList' id='roomCate'}
                    <li
                            class="im-cate-list-item
                            {eq name="cateId" value="$roomCate.id"}im-cate-list-item-active{/eq}"
                            onclick="setCate('{$roomCate.id}')"
                    >
                        {$roomCate.cate_name}
                    </li>
                {/volist}
            </ul>
        </div>
        <div class="im-box im-room">
            <div class="im-room-top clearfix">
                <div class="im-title im-room-top-title">
                    聊天室
                </div>
                <button class="im-room-top-new">
                    新建聊天室
                </button>
            </div>
            {eq name="roomListNum" value="0"}
                <div class="im-title im-room-no">暂无聊天室...</div>
            {else/}
                <ul class="im-room-list">
                    {volist name='roomList' id='room'}
                        <li class="im-room-list-item">
                            <div class="im-room-list-item-img">
                                <img src="{$room.room_img}">
                                <div class="im-room-list-item-img-layer">
                                    <div class="im-room-list-item-img-layer-title">
                                        进入聊天室
                                    </div>
                                </div>
                            </div>
                            <div class="im-room-list-item-main">
                                <div class="im-room-list-item-title">{$room.room_name}</div>
                                <div class="im-room-list-item-user clearfix">
                                    <div class="im-room-list-item-user-info">
                                        <img src="{$room.user_face}" class="im-room-list-item-user-face">
                                        <span class="im-room-list-item-user-name">{$room.nick_name}</span>
                                    </div>

                                    <div class="im-room-list-item-user-num">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        {$room.room_user_num}
                                    </div>
                                </div>
                            </div>
                        </li>
                    {/volist}
                    {for start="0" end="$roomListAddNum"}
                        <li class="im-room-list-item" style="visibility:hidden;"></li>
                    {/for}
                </ul>
                {$roomList|raw}
            {/eq}
        </div>

    </div>
<script>
    function setCate(id) {
        var url = location.href;
        url = updateUrlParam(url,'cateId',id);
        location.href = updateUrlParam(url,'page',1);
    }
</script>
</body>
</html>