{include 'header.tpl'}

<style>
    .table th {
        text-align: center;
    }

    .table td {
        text-align: center;
    }

    #preview {
        position: absolute;
        border: 1px solid #ccc;
        background: #333;
        padding: 5px;
        display: none;
        color: #fff;
    }
</style>

<ol class="breadcrumb">
    <li>好友查询</li>
    <li class="active"><a href="/admin/friend/friendlist">查询好友列表</a></li>
</ol>


<form method="get" action="/admin/friend/friendlist" class="form-inline">
    <table class="fTable">
        <tr>
            <th><span class="text-danger">*</span>UID</th>
            <td>
                <input type="text" name="user_id" value="" style="width: 120px;">
            </td>
            <th><span class="text-danger">*</span>库id</th>
            <td><input id="db_id" type="text" value="" name="db_id" style="width: 80px;"/></td>

            <td>
                <button type="submit">查询</button>
            </td>
        </tr>
    </table>
</form>

<div class="clearfix" style="height:30px;"></div>


<form id="unionForm" name="form" action="" method="post">
    <table class="table">
        <tr>
            <th>角色名称</th>
            <th>昵称</th>

            <th>等级</th>
            <th>性别</th>
            <th>签名</th>

            <th>上次登录时间</th>
            <th>注册时间</th>
        </tr>
        {foreach item=item from=$friend_list}
            <tr>
                <td>{$item.role_name}</td>
                <td>{$item.nickname}</td>

                <td>{$item.level}</td>
                <td>{$item.role_sex}</td>
                <td>{$item.sign}</td>

                <td>{$item.time_last_login}</td>
                <td>{$item.reg_time}</td>
            </tr>
        {/foreach}
    </table>
    <a href="/admin/friend/friendlist?page={$page-1}&user_id={$user_id}&db_id={$db_id}">上一页</a>  <a href="/admin/friend/friendlist?page={$page+1}&user_id={$user_id}&db_id={$db_id}">下一页</a>
</form>


{include 'footer.tpl'}
