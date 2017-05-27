{include 'header.tpl'}

<style type="text/css">
    form {
        padding: 10px;
    }
</style>

<form class="form-horizontal" role="form" method="post" action="/admin/red-activity/add-activity">
    <input type="hidden" name="red_id" value="{$redInfo.red_id}">
    <div class="form-group">
        <label  class="col-sm-2 control-label">活动名称</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="{$redInfo.activity_name}" name="activity_name" placeholder="请输入活动名称">
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">红包类型</label>
        <div class="col-sm-4">
             <select name="prize_type" disabled>
                 <option value="1" {if $redInfo.prize_type eq 1}selected{/if}>固定红包</option>
                 <option value="2" {if $redInfo.prize_type eq 2}selected{/if}>随机红包</option>
             </select>
        </div>
    </div>
    <div style="padding-left: 100px">
        <div  class="form-group">
            <div class="form-group">
                <label  class="col-sm-2 control-label">单个红包金额1</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  value="{$redInfo.total_money}" name="activity[1][per_money]" placeholder="请输入单个红包金额">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">总红包数1</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[1][total_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">每天个数限制1</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[1][limit_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">活动状态</label>
                <div class="col-sm-4">
                    <select name="activity[1][status]">
                        <option value="1" {if $redInfo.status eq 1}selected{/if}>启用</option>
                        <option value="-1" {if $redInfo.status eq -1}selected{/if}>停用</option>
                    </select>
                </div>
            </div>
        </div>

        <div  class="form-group">
            <div class="form-group">
                <label  class="col-sm-2 control-label">单个红包金额2</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  value="{$redInfo.total_money}" name="activity[2][per_money]" placeholder="请输入单个红包金额">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">总红包数2</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[2][total_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">每天个数限制2</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[2][limit_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">活动状态</label>
                <div class="col-sm-4">
                    <select name="activity[1][status]">
                        <option value="1" {if $redInfo.status eq 1}selected{/if}>启用</option>
                        <option value="-1" {if $redInfo.status eq -1}selected{/if}>停用</option>
                    </select>
                </div>
            </div>
        </div>

        <div  class="form-group">
            <div class="form-group">
                <label  class="col-sm-2 control-label">单个红包金额3</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  value="{$redInfo.total_money}" name="activity[3][per_money]" placeholder="请输入单个红包金额">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">总红包数3</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[3][total_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">每天个数限制3</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[3][limit_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">活动状态</label>
                <div class="col-sm-4">
                    <select name="activity[1][status]">
                        <option value="1" {if $redInfo.status eq 1}selected{/if}>启用</option>
                        <option value="-1" {if $redInfo.status eq -1}selected{/if}>停用</option>
                    </select>
                </div>
            </div>
        </div>

        <div  class="form-group">
            <div class="form-group">
                <label  class="col-sm-2 control-label">单个红包金额4</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  value="{$redInfo.total_money}" name="activity[4][per_money]" placeholder="请输入单个红包金额">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">总红包数4</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[4][total_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">每天个数限制4</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[4][limit_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">活动状态</label>
                <div class="col-sm-4">
                    <select name="activity[1][status]">
                        <option value="1" {if $redInfo.status eq 1}selected{/if}>启用</option>
                        <option value="-1" {if $redInfo.status eq -1}selected{/if}>停用</option>
                    </select>
                </div>
            </div>
        </div>

        <div  class="form-group">
            <div class="form-group">
                <label  class="col-sm-2 control-label">单个红包金额5</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  value="{$redInfo.total_money}" name="activity[5][per_money]" placeholder="请输入单个红包金额">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">总红包数5</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[5][total_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">每天个数限制5</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[5][limit_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">活动状态</label>
                <div class="col-sm-4">
                    <select name="activity[1][status]">
                        <option value="1" {if $redInfo.status eq 1}selected{/if}>启用</option>
                        <option value="-1" {if $redInfo.status eq -1}selected{/if}>停用</option>
                    </select>
                </div>
            </div>
        </div>
        <div  class="form-group">
            <div class="form-group">
                <label  class="col-sm-2 control-label">单个红包金额6</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  value="{$redInfo.total_money}" name="activity[6][per_money]" placeholder="请输入单个红包金额">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">总红包数6</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[6][total_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">每天个数限制6</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="{$redInfo.total_num}" name="activity[6][limit_num]" placeholder="请输入总红包数">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">活动状态</label>
                <div class="col-sm-4">
                    <select name="activity[1][status]">
                        <option value="1" {if $redInfo.status eq 1}selected{/if}>启用</option>
                        <option value="-1" {if $redInfo.status eq -1}selected{/if}>停用</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">连接地址</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="{$redInfo.merchant_link}" name="merchant_link" placeholder="请输入连接地址">
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">开始时间</label>
        <div class="col-sm-4">
            <input  type="text" value="{$redInfo.begin_time}" name="begin_time" id="begin_time" class="form_datetime" placeholder="请输入开始时间">
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-2 control-label">结束时间</label>
        <div class="col-sm-4">
            <input type="text" class="form_datetime" name="end_time" value="{$redInfo.end_time}" id="end_time" placeholder="请输入结束时间">
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">发放密码</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="pwd" value="" placeholder="请输入发放密码">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4" style="float:left">
            <button type="submit" name="ok" value="ok" class="btn btn-default">确定</button>
            <button type="reset" class="btn btn-default">取消</button>
        </div>
    </div>
</form>
<script>
$('#begin_time').datetimepicker();
$('#end_time').datetimepicker();
</script>

{include 'footer.tpl'}
