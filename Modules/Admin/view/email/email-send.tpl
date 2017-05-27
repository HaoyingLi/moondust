{include 'header.tpl'}
<div class="form-horizontal" style="border:2px solid #d2d6de;width: 100%;margin-top:30px;">
    <div class="form-group">
        <label  class="col-sm-2">用户:</label>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">用户Id:</span>    <input class="col-sm-3" type="text" value="" id="userId" name="userId">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2">邮件编辑:</label>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">邮件标题:</span>    <input class="col-sm-1" type="text" value="" id="emailTitle" name="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">邮件内容:</span>         <textarea class="col-sm-9" rows="4" id="emailContent" style="resize: none"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2">发送选项:</label>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">发送对象:</span>
            <select name="object" id="object" class="col-sm-1">
                <option value="1">用户</option>
                <option value="2">所有用户</option>
                {*<option value="3">在线用户</option>*}
            </select>
            <!--                <span class="col-sm-1">发送时间:</span>
                            <select name="date" id="date" class="col-sm-1">
                                <option value="1">即时发送</option>
                                <option value="2">定时发送</option>
                            </select>
                            <span class="col-sm-1">时间</span> <input class="col-sm-2" type="text" value="" id="timing" placeholder="即时发送为空，格式：" name=""> -->
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">发送次数:</span>        <input class="col-sm-1" type="text" value="1" id="times"  name="">
            <!--                <span class="col-sm-1">训练师等级:</span>        <input class="col-sm-1" type="text" value="" id="floorLevel" placeholder="全等级为空" name="">
                            <span class="col-sm-1"> —</span>        <input class="col-sm-1" type="text" value="" id="upperLevel" placeholder="全等级为空" name=""> -->
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2">资源附件:</label>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">金币:</span>    <input class="col-sm-1" type="text" value="" id="gold" placeholder="为空不修改" name="">
            <span class="col-sm-1">星辰:</span>        <input class="col-sm-1" type="text" value="" id="dust" placeholder="为空不修改" name="">
            <span class="col-sm-1">水系糖果:</span>    <input class="col-sm-1" type="text" value="" id="candy_water" placeholder="为空不修改" name="">
            <span class="col-sm-1">火系糖果:</span>    <input class="col-sm-1" type="text" value="" id="candy_fire" placeholder="为空不修改" name="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <span class="col-sm-1">风系糖果:</span>    <input class="col-sm-1" type="text" value="" id="candy_wind" placeholder="为空不修改" name="">
            <span class="col-sm-1">毒系糖果:</span>    <input class="col-sm-1" type="text" value="" id="candy_poison" placeholder="为空不修改" name="">
            <span class="col-sm-1">木系糖果:</span>    <input class="col-sm-1" type="text" value="" id="candy_wood" placeholder="为空不修改" name="">
            <span class="col-sm-1">电系糖果:</span>    <input class="col-sm-1" type="text" value="" id="candy_electric" placeholder="为空不修改" name="">
            <span class="col-sm-1">石系糖果:</span>   <input class="col-sm-1" type="text" value="" id="candy_stone" placeholder="为空不修改" name="">
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-sm-2">道具附件</label>
    </div>
    <div class="from-group">
        <div class="col-sm-5 row">
            <span class="col-sm-2" style="margin-top:20px">道具:</span>
            <select name="tool_type" id="tool_type" class="col-sm-2" style="margin-top:20px">
                <option value="0">无</option>
                <option value="2">熏香</option>
                <option value="3">诱饵</option>
                <option value="4">恢复剂</option>
                <option value="5">复活石</option>
                <option value="6">小草莓</option>
                <option value="7">孵化器</option>
                <option value="8">多倍经验</option>
                <option value="9">宠物蛋</option>
                <option value="12">抓捕球</option>
                <option value="13">玩具</option>
                <option value="14">喂食食物</option>
                <option value="16">材料</option>
                <option value="17">配方</option>
                <option value="18">晶石</option>
            </select>
            <select name="tool" id="tool" class="col-sm-2" style="margin-top:20px">
                <option value="0">无</option>
            </select>
        </div>
        <div class="col-sm-offset-5">
            <span class="col-sm-1" style="margin-top:20px">数量:</span>
            <input type="text" name="tool_num" id="tool_num" class="col-sm-2" style="margin-top:20px">
        </div>
        <div class="col-sm-offset-10">
            <button class="btn btn-success" id="add" style="margin-top:20px">添加</button>
        </div>

    </div>
    <div class="from-group">
        <div class="col-sm-12">
            <table class="table table-striped">
                <thead>
                <th><input type="checkbox"  id="all_check"></th>
                <th>名称</th>
                <th>数量</th>
                </thead>
                <tbody class="tbody">

                </tbody>
            </table>
            <button class="btn btn-danger" id="del">移除</button>
        </div>
    </div>
</div>

<input type="button" value="确认修改" id="confirm" class="btn-success center-block" style="margin-top:30px;">

<script>
    $(document).on('click', '#confirm',function(){

        var userId = $("#userId").val();
        var emailContent = $("#emailContent").val();
        var emailTitle = $("#emailTitle").val();
        var gold = $("#gold").val();
        var dust = $("#dust").val();
        var candy_water = $("#candy_water").val();
        var candy_fire = $("#candy_fire").val();
        var candy_wind = $("#candy_wind").val();
        var candy_poison = $("#candy_poison").val();
        var candy_wood = $("#candy_wood").val();
        var candy_electric = $("#candy_electric").val();
        var candy_stone = $("#candy_stone").val();
        var object = $("#object").find("option:selected").val();
        var times = $("#times").val();
//        var floorLevel = $("#floorLevel").val();
//        var upperLevel = $("#upperLevel").val();


        var arr_tool = {};

        $(".t_check:checked").each(function(index, el) {
            var tool_id = $(this).val();
            var num = $(this).parents('td').next().next().find("input").val();
            if( arr_tool[tool_id] ) arr_tool[tool_id] = parseInt(arr_tool[tool_id])+parseInt(num);else arr_tool[tool_id] = num;

        });

        console.log(arr_tool);


        $.ajax({
            url: '/admin/email/ajax-email-send',
            type: 'POST',
            dataType: 'json',
            data: {
                object : object,
                user_id: userId,
                content : emailContent,
                title : emailTitle,
                gold: gold,
                dust: dust,
                candy_water: candy_water,
                candy_fire: candy_fire,
                candy_wind: candy_wind,
                candy_poison: candy_poison,
                candy_wood: candy_wood,
                candy_electric: candy_electric,
                candy_stone: candy_stone,
//                floor_level: floorLevel,
//                upper_level: upperLevel,
                times: times,
                arr_tool: arr_tool
            },

            success: function (data) {

                if( data.code == 0 ) {
                    alert('填写邮件奖励内容和标题');
                }
                else if( data.code == 1 ) {
                    alert('请填写用户Id');
                }
                else if( data.code == 2 ) {
                    alert('发送邮件成功');
                }
                else if( data.code == 3 ) {
                    alert('本版本功能还未开发');
                }
            }
        });



    });

    $(function(){
        $("#tool_type").change(function() {
            $.ajax({
                url: "/admin/prop/get-prop",
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
            if($("#tool").val()!=0 && parseInt($("#tool_num").val()) > 0) {

                var str_tr = "<tr class='t_tr'><td><input type='checkbox' value='"+$("#tool").val()+"' class='t_check' /></td><td>"+$("#tool option:selected").html()+"</td><td><input type='text' disabled = 'true' style= 'border:0px; background-color:transparent ' value='"+parseInt($("#tool_num").val())+"' /></td></tr>";
                $(".tbody").append(str_tr);

            }
        });

        $("#del").click(function() {
            $(".t_check:checked").not("#all_check").parents("tr").remove();
            $(".p_check:checked").not("#all_check").parents("tr").remove();
        });

        $("#all_check").change(function() {
            $(".t_check").prop("checked",$(this).prop("checked"));
            $(".p_check").prop("checked",$(this).prop("checked"));
        });
    });
</script>

{include 'footer.tpl'}