<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style type="text/css">
        body {
            background-color: #1C86D1;
            padding: 0;
            margin: 0;
        }

        .top_bar {
            color: #fff;
            height: 40px; /*overflow:hidden;*/
        }

        .top_bar a {
            color: #fff;
            text-decoration: none;
        }

        #sider_a {
            width: 220px;
            float: left;
            cursor: pointer;
            padding-left: 10px;
            height: 40px;
            font: 25px/30px Arial, Helvetica, sans-serif;
            line-height: 40px;
        }

        #main {
            /*width: 780px;*/
            float: left;
            text-align: left;
            height: 40px;
            line-height: 40px;
        }

        #main a {
            width: 80px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            line-height: 40px;
        }

        #main a.current {
            background: #fff;
            color: #000;
        }

        #sider_b {
            width: 200px;
            float: right;
            height: 40px;
            line-height: 40px;
            padding: 0 10px;
            text-align: right;
        }

    </style>
    <script src="/js/flib.js"></script>
</head>

<body>

<div class="top_bar">
    <div id="sider_a" onclick="top.location = '/';">
        萌宠大爆炸
    </div>
    <div id="main">
        {foreach from=$topItems key=key item=item}
            &nbsp; <a target="left_frame"  href="{$item.url}">
                {$item.name}
            </a>
        {/foreach}
    </div>
    <div id="sider_b">
        {*短信条数：{$balance}&nbsp;*}
        GM，
        <a target="_top" href="/admin/auth/logout">退出</a>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var top_menu_a = $('#main').find('a');
        top_menu_a.click(function () {

//            var url_link = $(this).attr('href');

            top_menu_a.removeClass();
            $(this).addClass('current');

            var main_frame = parent.document.getElementById("main_frame");
            if (main_frame) {
                var main_frame_col = main_frame.cols;
                if (main_frame_col.substr(0, main_frame_col.indexOf(',')) == 0) {
                    parent.toggle_left_panel();
                }

//                parent.document.getElementById("left_frame").location = url_link;
            }

            return true;
        });

//        setTimeout(top_menu_a.eq(0).trigger('click'), 1000);
    });
</script>
</body>
</html>