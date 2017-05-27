{include 'header.tpl'}
<div class="row form-inline">
    <div class="col-md-12"><label>用户id:</label><input type="text" name="username" id="username" class="form-control"></div>
    <div class="col-md-6">
        <div class="col-md-5 row">
            <label>精灵:</label>
            <select name="pet_type" id="pet_type" class="form-control">
                <option value="0">无</option>
                <option value="1">水</option>
                <option value="2">火</option>
                <option value="3">风</option>
                <option value="4">毒</option>
                <option value="5">木</option>
                <option value="6">电</option>
                <option value="7">土</option>
            </select>
            <select name="pet" id="pet" class="form-control">
                <option value="0">无</option>

            </select>
        </div>
        <div class="col-md-offset-5">

            <label for="num">数量:</label>
            <input type="text" name="pet_num" id="pet_num" class="form-control">
        </div>
        <div class="col-md-5 row" style="margin-top:20px">
            <label>道具:</label>
            <select name="tool_type" id="tool_type" class="form-control">
                <option value="0">无</option>
                <option value="2">熏香</option>
                <option value="3">诱饵</option>
                <option value="4">恢复剂</option>
                <option value="5">复活石</option>
                <option value="6">小草莓</option>
                <option value="7">孵化器</option>
                <option value="8">多倍经验</option>
                <option value="9">宠物蛋</option>
                <option value="12">抓捕食物</option>
                <option value="13">玩具</option>
                <option value="14">喂食食物</option>
                <option value="15">雷达</option>
                <option value="16">材料</option>
                <option value="17">配方</option>
                <option value="18">技能书</option>
                <option value="19">变性手册</option>
                <option value="20">宠物羁绊</option>
                <option value="21">事件凭证</option>
                <option value="22">追踪</option>
                <option value="23">实景AR</option>
            </select>
            <select name="tool" id="tool" class="form-control">
                <option value="0">无</option>
            </select>
        </div>
        <div class="col-md-offset-5" style="margin-top:20px">

            <label for="num">数量:</label>
            <input type="text" name="tool_num" id="tool_num" class="form-control">
        </div>
        <button class="btn btn-success" id="add" style="margin-top:20px">添加</button>
    </div>
    <div class="col-md-6">
        <table class="table table-striped">
            <thead>
            <th><input type="checkbox"  id="all_check"></th>
            <th>名称</th>
            <th>数量</th>
            </thead>
            <tbody class="tbody">

            </tbody>
        </table>

        <button class="btn btn-success" id="send">确认发送</button>
        <button class="btn btn-danger" id="del">移除</button>
    </div>
</div>
<script>
    $(function() {

        $("#pet_type").change(function() {
            $.ajax({
                url: "get-pet",
                type: 'get',
                dataType: 'json',
                data: {
                    type: $("#pet_type").val()
                },
                success: function(data) {
                    if(data.code == 1) {
                        $("#pet").html("<option value=0>无</option>"+data.data);
                    }else{
                        $("#pet").html("<option value=0>无</option>");
                    }
                }
            });

        });

        $("#tool_type").change(function() {
            $.ajax({
                url: "get-prop",
                type: 'get',
                dataType: 'json',
                data: {
                    type: $("#tool_type").val()
                },
                success: function(data) {
                    if(data.code == 1) {
                        $("#tool").html("<option value=0>无</option>"+data.data);
                    }else{
                        $("#tool").html("<option value=0>无</option>");
                    }
                }
            });

        });

        $("#add").click(function() {
            if($("#pet").val()!=0 && parseInt($("#pet_num").val()) > 0) {

                var str_tr = "<tr class='t_tr'><td><input type='checkbox' value='"+$("#pet").val()+"' class='p_check' /></td><td>"+$("#pet option:selected").html()+"</td><td><input type='text' value='"+parseInt($("#pet_num").val())+"' /></td></tr>";
                $(".tbody").append(str_tr);

            }
            if($("#tool").val()!=0 && parseInt($("#tool_num").val()) > 0) {

                var str_tr = "<tr class='t_tr'><td><input type='checkbox' value='"+$("#tool").val()+"' class='t_check' /></td><td>"+$("#tool option:selected").html()+"</td><td><input type='text' value='"+parseInt($("#tool_num").val())+"' /></td></tr>";
                $(".tbody").append(str_tr);



            }
        });

        $("#all_check").change(function() {
            $(".t_check").prop("checked",$(this).prop("checked"));
            $(".p_check").prop("checked",$(this).prop("checked"));
        });
        $("#del").click(function() {
            $(".t_check:checked").not("#all_check").parents("tr").remove();
            $(".p_check:checked").not("#all_check").parents("tr").remove();
        });

        $("#send").click(function() {
            var arr_tool = {};
            var arr_pet = {};

            $(".t_check:checked").each(function(index, el) {
                var tool_id = $(this).val();
                var num = $(this).parents('td').next().next().find("input").val();
                if( arr_tool[tool_id] ) arr_tool[tool_id] = parseInt(arr_tool[tool_id])+parseInt(num);else arr_tool[tool_id] = num;

            });
            $(".p_check:checked").each(function(index, el) {
                var pet_id = $(this).val();
                var num = $(this).parents('td').next().next().find("input").val();
                if( arr_pet[pet_id] ) arr_pet[pet_id] = parseInt(arr_pet[pet_id])+parseInt(num);else arr_pet[pet_id] = num;

            });
            $(".u_check:checked").each(function(index, el) {
                arr_user.push($(this).val());

            });
            if( ($.isEmptyObject(arr_tool) && $.isEmptyObject(arr_pet)) || !$("#username").val()) {
                alert("没选择用户或者道具");return;
            }
            $.ajax({
                url: 'do-send',
                type: 'post',
                data: {
                    arr_tool:arr_tool, username:$("#username").val(),arr_pet:arr_pet
                },
                success:function(data) {
//                    console.log(data);
                    alert("添加成功");
                }
            });

        });


    })
</script>
{include 'footer.tpl'}