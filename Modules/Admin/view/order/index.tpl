{include 'header.tpl'}
<div class="row form-inline">
    <div class="col-md-12"><label>用户id:</label>
        <form action="" method="post">
            <input type="text" name="userId" id="userId" class="form-control">
            <input type="submit" value="搜索">

        </form>
    </div>
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
            <th>订单号</th>
            <th>下单时间</th>
            <th>金额</th>
            <th>状态</th>
            </thead>
            <tbody>
            {foreach from=$orders item=order}
                <tr>
                    <td>{$order.out_trade_no}</td>
                    <td>{$order.create_time|date_format:"%y/%m/%d %T"}</td>
                    <td>{$order.amount}</td>
                    <td>{if $order.trade_status eq 1}失败{else}成功{/if}</td>
                </tr>

            {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include 'footer.tpl'}
