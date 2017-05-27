<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script type="text/javascript">
        if (top.location.href != location.href) {
            location.replace(location.href);
        }
    </script>

    <script charset="utf-8" src="/js/jquery.min.js" type="text/javascript"></script>
    <script charset="utf-8" src="/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- Custom styles for this template -->
    <link href="/css/signin.css" rel="stylesheet">

    <style type="text/css">
        th { font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <form class="form-signin" role="form" action="/admin/auth/login" method="post" id="form-login">
        <h2 class="form-signin-heading">后台登录</h2>
        <input name="username" type="text" class="form-control" placeholder="请输入账号" required autofocus>
        <input name="password" type="password" class="form-control" placeholder="请输入密码" required>
        <button class="btn btn-primary" type="submit">登录</button>
    </form>

</div>
<!-- /container -->
<script type="text/javascript">
    $(function () {
        $('#login-form').submit(function () {

            $('#form-login').ajaxSubmit({
                        dataType: 'json',
                        data: { in_ajax: 1 },
                        success: function (retData) {
                            $('#result-count').html(retData.count);
                        },
                        error: function () {
                            alert('发生错误。');
                        }
                    }
            );

            return false;
        });
    });
</script>
</body>
</html>