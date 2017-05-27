{include 'header.tpl'}
<div class="row form-inline">

    <table class="table table-striped">
        <thead>
        <th>用户id</th>
        <th>异常时间</th>
        <th>角色名</th>
        <th>等级</th>
        <th>异常次数</th>
        <th>操作</th>
        </thead>
        <tbody>
        {foreach from=$users item=user}
            <tr>
                <td>{$user['user_id']}</td>
                <td>{$user['create_time']|date_format:"%y/%m/%d %T"}</td>
                <td>{$user['role_name']}</td>
                <td>{$user['level']}</td>
                <td class="count">{$user['count']}</td>
                <td><input type='text' class='hour' placeholder="小时"><a href='###' user_id='{$user['user_id']}'>封号</a></td>
            </tr>

        {/foreach}
        </tbody>
    </table>
</div>
<script>
    $(function(){




        $(".hour").blur(function(){
            $(this).next().attr("href","feng-hao?user_id="+$(this).next().attr("user_id")+"&hour="+$(this).val()+"&count="+$(this).parent().prev().html());
        })

    })
</script>
{include 'footer.tpl'}