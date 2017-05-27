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
    <li>红包管理</li>
    <li class="active"><a href="/admin/red-activity/create-activity">创建活动</a></li>
    <li><a href="/admin/user/list?g=3">高级VIP用户</a></li>
</ol>


<form method="get" action="/admin/user/list" class="form-inline">
    <table class="fTable">
        <tr>
            <th>时间</th>
            <td style="width: 220px;">
                <input type="text" class="date-picker" name="begin_date" value="" style="width: 85px;" />
                <input type="text" class="date-picker" name="end_date" value="" style="width: 85px; "/>
            </td>
            <th>UID</th>
            <td>
                <input type="text" name="uid" value="" style="width: 30px;">
            </td>
            <th>登录名</th>
            <td><input id="form-w" type="text" value="" name="w" style="width: 80px;"/></td>

            <td>
                <button type="submit">查找</button>
            </td>
        </tr>
    </table>
</form>

<div class="clearfix" style="height:30px;"></div>


<form id="unionForm" name="form" action="" method="post">
<table class="table">
    <tr>
        <th>活动ID</th>
        <th>活动名称</th>

        <th>总金额</th>
        <th>总红包数</th>
        <th>连接地址</th>

        <th>开始时间</th>
        <th>结束时间</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    {foreach item=item from=$redActivityList}
        <tr>
            <td>{$item.red_id}</td>
            <td>{$item.activity_name}</td>

            <td>{$item.total_money}</td>
            <td>{$item.total_num}</td>
            <td>{$item.merchant_link}</td>
e
            <td>{$item.begin_time}</td>
            <td>{$item.end_time}</td>
            <td>{if $item.status eq 1}<font style="color: green">启用</font>{else}<font style="color: red">停用</font>{/if}</td>
            <td><a href="/admin/red-activity/add-activity?red_id={$item.red_id}">编辑</a>  <a href="/admin/red-activity/delete?red_id={$item.red_id}">删除</a></td>
        </tr>
    {/foreach}
</table>
</form>


{include 'footer.tpl'}
