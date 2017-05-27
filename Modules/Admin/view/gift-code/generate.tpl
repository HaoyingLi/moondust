{include 'header.tpl'}

<style type="text/css">
    form {
        padding: 10px;
    }
</style>

<form class="form-horizontal" role="form" method="post" action="/admin/gift-code/code-gen">
    <div class="form-group">
        <label  class="col-sm-2 control-label">礼包数量</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="gift_num" placeholder="请输入要生成的礼包数量">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">礼包类型</label>
        <div class="col-sm-4">
             <select class="gift_type" name="gift_type">
                 <option value="0"></option>
                 {foreach $gift_type as $key => $val}
                 <option value="{$val}">{$val}号礼包</option>
                 {/foreach}
             </select>
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-2 control-label">礼包内容</label>
        <div class="col-sm-4">
            <textarea disabled  placeholder="礼包内容" class="gift_content" rows="10"></textarea>
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
        <div class="col-sm-4" style="float:left">
            <button type="submit" class="btn btn-default">确定</button>
            <button type="reset" class="btn btn-default">取消</button>
        </div>
    </div>
</form>
<script>
$('#begin_time').datetimepicker();
$('#end_time').datetimepicker();
</script>

<script>
    $(document).on('change', '.gift_type',function(){
        var type = $(".gift_type").val();
        var content = $(".gift_content");

        $.ajax({
            url: '/admin/gift-code/ajax-get-gift-content',
            type: 'POST',
            dataType: 'json',
            data: {
                type: type
            },
            success: function (data) {
                if( data.code == 0 ) {
                    content.val( data.data );
                }
                else if( data.code == 1 ) {
                    alert( data.data );
                }
            }
        });
    });
</script>

{include 'footer.tpl'}
