<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css">
        body { margin: 0; padding: 0; }
        .switch-bar { position: fixed; width: 10px; height: 800px; }

        .switch-bar a { background-color: #F2F2F2; border: 1px solid #E5E2E4; display: block; width: 8px; height: 800px; cursor: hand }
        .switch-bar a:hover { background-color: #FEFEB4; border-color: #FDEC4E; }
        .switch-bar span { background: url(/img/decorate_v3.png) 0px -120px; display: block; height: 48px; margin-top: 230px; text-indent: -999em; width: 8px; }
    </style>
</head>
<body onclick="top.toggle_left_panel()">
<div class="switch-bar" style="margin-top: 0; top: 0; opacity: 1;">
    <a href="#" style="height: 1400px;"><span id="btn-toggle-frame">点此展开/折叠侧栏</span></a>
</div>
</body>
</html>