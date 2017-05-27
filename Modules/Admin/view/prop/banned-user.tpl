{include 'header.tpl'}
<div class="row form-inline">

    <table class="table table-striped">
        <thead>
        <th>用户id</th>
        <th>剩余时间/小时</th>
        <th>角色名</th>
        <th>等级</th>
        <th>异常次数</th>
        <th>操作</th>
        </thead>
        <tbody>
        {foreach from=$users item=user}
            <tr>
                <td>{$user['user_id']}</td>
                <td>{$user['time']}</td>
                <td>{$user['role_name']}</td>
                <td>{$user['level']}</td>
                <td class="count">{$user['count']}</td>
                <td><a href="jiefeng?id={$user['user_id']}" >解封</a> </td>
            </tr>

        {/foreach}
        </tbody>
    </table>
</div>
<script>
    $(function(){



    })
</script>
{include 'footer.tpl'}