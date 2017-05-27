{include 'header.tpl'}
<div class="row form-inline">
    <form action="" method="post">
        <div class="col-md-12"><label>用户id:</label><input type="text" name="userId" id="userId" class="form-control">
            <input type="submit" value="发送">
        </div>
        
    </form>
    <input type="button" id="get" value="还原用户道具数量,谨慎操作" class="btn btn-danger">

</div>
<script>
    $(function(){
        $("#get").click(function(){
            location="get-props?user_id="+$("#userId").val();
        });
    })
</script>
{include 'footer.tpl'}