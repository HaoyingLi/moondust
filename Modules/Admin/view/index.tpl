<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript">
        function toggle_left_panel() {

            var main_frame_col = parent.document.getElementById("main_frame").cols;
            if (main_frame_col.substr(0, main_frame_col.indexOf(',')) == 0) {
                parent.document.getElementById("main_frame").cols = "180,10,*";
                try {
                    window.frames['toggle_frame'].document.getElementById('btn-toggle-frame').style.background = "url(/img/decorate_v3.png) 0px -120px";
                } catch (e) { }
            } else {
                parent.document.getElementById("main_frame").cols = "0,10,*";

                try {
                    window.frames['toggle_frame'].document.getElementById('btn-toggle-frame').style.background = "url(/img/decorate_v3.png) -85px -120px";
                } catch (e) { }
            }
        }
    </script>
    {*<script charset="utf-8" src="/js/jquery.min.js" type="text/javascript"></script>*}
    {*<link rel="stylesheet" href="/css/flib.css"/>*}
    {*<script charset="utf-8" src="/js/flib.js?v=1"></script>*}

    {*<script src="/js/jquery.colorbox.js" type="text/javascript"></script>*}
</head>
<frameset rows="40,*" framespacing=0 frameborder=no border=0 id=index>
    <frame id="top_frame" name="top_frame" src="/admin/main/top" noresize scrolling="no">
    <frameset border="0" framespacing="0" id="main_frame" frameborder="no" cols="180,10,*">
        <frame id="left_frame" name="left_frame" src="/admin/main/left" noresize scrolling="no">
        <frame src="/admin/main/border" name="toggle_frame" id="toggle_frame" scrolling="no" noresize>
        <frame id="right_panel" name="right_panel" src="/admin/main/main">
    </frameset>
</frameset>
<body></body>
</html>
