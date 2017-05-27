{include 'header.tpl'}

<style type="text/css">
    form {
        padding: 10px;
    }
</style>

<form class="form-horizontal" role="form" method="post" action="/admin/gift-code/code-gen">
    <div class="form-group">
        <label  class="col-sm-2 control-label">当前公告</label>
        <div class="col-sm-4">
            <textarea disabled  placeholder="当前公告" class="current_notice" rows="15" cols="100">{$current_notice}</textarea>
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">修改公告</label>
        <div class="col-sm-4">
            <textarea placeholder="修改公告" class="notice" rows="15" cols="100"></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4" style="float:left">
            <button type="button" class="btn btn-default" id="modify-notice">修改</button>
        </div>
    </div>
</form>

<script>
    $(document).on('click', '#modify-notice',function(){
        var notice = $(".notice").val();
        var current_notice = $(".current_notice");
        $.ajax({
            url: '/admin/system/ajax-modify-notice',
            type: 'POST',
            dataType: 'json',
            data: {
                notice: notice
            },
            success: function (data) {
                alert( 'success' );
                current_notice.val( data.data );
            }
        });
    });
</script>

{include 'footer.tpl'}
