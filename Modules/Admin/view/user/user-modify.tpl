{include 'header.tpl'}

<style type="text/css">
    form {
        padding: 10px;
    }
</style>

<form class="form-horizontal" role="form" method="post" action="/admin/gift-code/code-gen">
    <div class="form-group">
        <label  class="col-sm-2 control-label">short id:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="short_id" id="short_id" placeholder="short id">
        </div>
        <button type="button" id="search_role" class="btn btn-default">搜索</button>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">角色信息</label>
        <div class="col-sm-5">
            {*<textarea disabled  placeholder="角色信息" class="role_info" rows="20" cols="100"></textarea>*}
            <div class="role_info"></div>
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label"><span class="text-danger">*</span>库id</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="db_id" id="db_id" placeholder="库id,查询用户资源时请按上面搜索出来的库id进行填写">
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label"><span class="text-danger">*</span>用户id</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="user_id" id="user_id" placeholder="用户id,查询用户资源时请按上面搜索出来的用户id进行填写">
        </div>
        <button type="button" id="search_resource" class="btn btn-default">搜索</button>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">星辰</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="dust" id="dust" placeholder="星辰">
        </div>
        <div id="dust_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">水系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_water" id="candy_water" placeholder="水系糖果">
        </div>
        <div id="candy_water_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">火系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_fire" id="candy_fire" placeholder="火系糖果">
        </div>
        <div id="candy_fire_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">风系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_wind" id="candy_wind" placeholder="风系糖果">
        </div>
        <div id="candy_wind_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">毒系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_poison" id="candy_poison" placeholder="毒系糖果">
        </div>
        <div id="candy_poison_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">木系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_wood" id="candy_wood" placeholder="木系糖果">
        </div>
        <div id="candy_wood_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">电系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_electric" id="candy_electric" placeholder="电系糖果">
        </div>
        <div id="candy_electric_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">土系糖果</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="candy_stone" id="candy_stone" placeholder="土系糖果">
        </div>
        <div id="candy_stone_redis"></div>
    </div>


    <div class="form-group">
        <label  class="col-sm-2 control-label">食物</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="food" id="food" placeholder="食物">
        </div>
        <div id="food_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">木材</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="wood" id="wood" placeholder="木材">
        </div>
        <div id="wood_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">铁矿</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="iron" id="iron" placeholder="铁矿">
        </div>
        <div id="iron_redis"></div>
    </div>

    <div class="form-group">
        <div class="col-sm-4" style="float:left">
            <button type="button" class="btn btn-default" id="modify_resource">修改资源</button>
        </div>
    </div>


    <div class="form-group">
        <label  class="col-sm-2 control-label">玩家等级</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" value="" name="level" id="level" placeholder="等级">
        </div>
        <div id="level_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">经验</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="exp" id="exp" placeholder="经验">
        </div>
        <div id="exp_redis"></div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">金币</label>
        <div class="col-sm-4">
            <input type="text" class="form-control"  value="" name="gold" id="gold" placeholder="金币">
        </div>
        <div id="gold_redis"></div>
    </div>

    <div class="form-group">
        <div class="col-sm-4" style="float:left">
            <button type="button" class="btn btn-default" id="modify_user">修改玩家信息</button>
        </div>
    </div>

    <div class="form-group">
        <label  class="col-sm-2 control-label">宠物id比对</label>
        <div class="col-sm-4">
            <textarea disabled  placeholder="数据库宠物id" class="mysql_pet_id" id="mysql_pet_id" rows="20" cols="30"></textarea>
            <textarea disabled  placeholder="缓存宠物id" class="redis_pet_id" id="redis_pet_id" rows="20" cols="30"></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4" style="float:left">
            <button type="button" class="btn btn-danger" id="flush_user_pet">数据库刷新宠物数据到缓存,因为数据库有五分钟或更高延迟，请谨慎操作</button>
        </div>
    </div>

</form>

<script>
    $(document).on('click', '#search_role',function(){
        var short_id = $("#short_id").val();
        var content = $(".role_info");
        if( short_id == '' ) {
            alert('空id');
            return;
        }
        $.ajax({
            url: '/admin/user/ajax-get-user-info',
            type: 'POST',
            dataType: 'json',
            data: {
                short_id: short_id
            },
            success: function (data) {
                if( data.code == 0 ) {
                    var tbody = '';
                    $.each(data.data,function(name,value) {
                        tbody += '库ID:'+value.db+'  ';
                        tbody += '用户id:'+value.user_id+'  ';
                        tbody += '角色名:'+value.role_name+'  ';
                        tbody += '等级:'+value.level+'  ';
                        tbody += '经验:'+value.exp+'  ';
                        tbody += '金币:'+value.gold+'  ';
                        tbody += "<br/>";
                    });
                    content.html( tbody );
                }
                else if( data.code == 1 ) {
                    alert( data.data );
                }
            }
        });
    });

    $(document).on('click', '#search_resource',function(){
        var db_id = $("#db_id").val();
        var user_id = $("#user_id").val();
        if( db_id == '' ) {
            alert('请填写库id');
            return;
        }
        if( user_id == '' ) {
            alert('请填写用户id');
            return;
        }

        var dust = $("#dust");
        var candy_water = $("#candy_water");
        var candy_fire = $("#candy_fire");
        var candy_wind = $("#candy_wind");
        var candy_poison = $("#candy_poison");
        var candy_wood = $("#candy_wood");
        var candy_electric = $("#candy_electric");
        var candy_stone = $("#candy_stone");
        var food = $("#food");
        var wood = $("#wood");
        var iron = $("#iron");

        var level = $("#level");
        var exp = $("#exp");
        var gold = $("#gold");

        var mysql_pet_id = $("#mysql_pet_id");
        var redis_pet_id = $("#redis_pet_id");

        var dust_redis = $("#dust_redis");
        var candy_water_redis = $("#candy_water_redis");
        var candy_fire_redis = $("#candy_fire_redis");
        var candy_wind_redis = $("#candy_wind_redis");
        var candy_poison_redis = $("#candy_poison_redis");
        var candy_wood_redis = $("#candy_wood_redis");
        var candy_electric_redis = $("#candy_electric_redis");
        var candy_stone_redis = $("#candy_stone_redis");
        var food_redis = $("#food_redis");
        var wood_redis = $("#wood_redis");
        var iron_redis = $("#iron_redis");

        var level_redis = $("#level_redis");
        var exp_redis = $("#exp_redis");
        var gold_redis = $("#gold_redis");

        $.ajax({
            url: '/admin/user/ajax-get-user-resource',
            type: 'POST',
            dataType: 'json',
            data: {
                db_id: db_id,
                user_id: user_id
            },
            success: function (data) {
                if( data.code == 0 ) {
                    dust.val( data.data.dust );
                    candy_water.val( data.data.candy_water );
                    candy_fire.val( data.data.candy_fire );
                    candy_wind.val( data.data.candy_wind );
                    candy_poison.val( data.data.candy_poison );
                    candy_wood.val( data.data.candy_wood );
                    candy_electric.val( data.data.candy_electric );
                    candy_stone.val( data.data.candy_stone );
                    food.val( data.data.food );
                    wood.val( data.data.wood );
                    iron.val( data.data.iron );
                    level.val( data.data.level );
                    exp.val( data.data.exp );
                    gold.val( data.data.gold );

                    mysql_pet_id.val( data.data.mysql_pet_id );
                    redis_pet_id.val( data.data.redis_pet_id );

                    dust_redis.html( data.data.dust_redis );
                    candy_water_redis.html( data.data.candy_water_redis );
                    candy_fire_redis.html( data.data.candy_fire_redis );
                    candy_wind_redis.html( data.data.candy_wind_redis );
                    candy_poison_redis.html( data.data.candy_poison_redis );
                    candy_wood_redis.html( data.data.candy_wood_redis );
                    candy_electric_redis.html( data.data.candy_electric_redis );
                    candy_stone_redis.html( data.data.candy_stone_redis );
                    food_redis.html( data.data.food_redis );
                    wood_redis.html( data.data.wood_redis );
                    iron_redis.html( data.data.iron_redis );
                    level_redis.html( data.data.level_redis );
                    exp_redis.html( data.data.exp_redis );
                    gold_redis.html( data.data.gold_redis );
                }
                else if( data.code == 1 ) {
                    alert( data.data );
                }
            }
        });
    });

    $(document).on('click', '#modify_resource',function(){
        var db_id = $("#db_id").val();
        var user_id = $("#user_id").val();

        var dust = $("#dust");
        var candy_water = $("#candy_water");
        var candy_fire = $("#candy_fire");
        var candy_wind = $("#candy_wind");
        var candy_poison = $("#candy_poison");
        var candy_wood = $("#candy_wood");
        var candy_electric = $("#candy_electric");
        var candy_stone = $("#candy_stone");
        var food = $("#food");
        var wood = $("#wood");
        var iron = $("#iron");


        var dust_redis = $("#dust_redis");
        var candy_water_redis = $("#candy_water_redis");
        var candy_fire_redis = $("#candy_fire_redis");
        var candy_wind_redis = $("#candy_wind_redis");
        var candy_poison_redis = $("#candy_poison_redis");
        var candy_wood_redis = $("#candy_wood_redis");
        var candy_electric_redis = $("#candy_electric_redis");
        var candy_stone_redis = $("#candy_stone_redis");
        var food_redis = $("#food_redis");
        var wood_redis = $("#wood_redis");
        var iron_redis = $("#iron_redis");

        $.ajax({
            url: '/admin/user/ajax-modify-user-resource',
            type: 'POST',
            dataType: 'json',
            data: {
                db_id: db_id,
                user_id: user_id,
                dust: dust.val(),
                candy_water: candy_water.val(),
                candy_fire: candy_fire.val(),
                candy_wind: candy_wind.val(),
                candy_poison: candy_poison.val(),
                candy_wood: candy_wood.val(),
                candy_electric: candy_electric.val(),
                candy_stone: candy_stone.val(),
                food: food.val(),
                wood: wood.val(),
                iron: iron.val()
            },
            success: function (data) {
                alert( data.data );

                dust_redis.html( dust.val() );
                candy_water_redis.html( candy_water.val() );
                candy_fire_redis.html( candy_fire.val() );
                candy_wind_redis.html( candy_wind.val() );
                candy_poison_redis.html( candy_poison.val() );
                candy_wood_redis.html( candy_wood.val() );
                candy_electric_redis.html( candy_electric.val() );
                candy_stone_redis.html( candy_stone.val() );
                food_redis.html( food.val() );
                wood_redis.html( wood.val() );
                iron_redis.html( iron.val() );
            }
        });
    });

    $(document).on('click', '#modify_user',function(){
        var db_id = $("#db_id").val();
        var user_id = $("#user_id").val();

        var level = $("#level");
        var exp = $("#exp");
        var gold = $("#gold");

        var level_redis = $("#level_redis");
        var exp_redis = $("#exp_redis");
        var gold_redis = $("#gold_redis");

        $.ajax({
            url: '/admin/user/ajax-modify-user',
            type: 'POST',
            dataType: 'json',
            data: {
                db_id: db_id,
                user_id: user_id,
                level: level.val(),
                exp: exp.val(),
                gold: gold.val()
            },
            success: function (data) {
                alert( data.data );

                level_redis.html( level.val() );
                exp_redis.html( exp.val() );
                gold_redis.html( gold.val() );
            }
        });
    });

    $(document).on('click', '#flush_user_pet',function(){
        var db_id = $("#db_id").val();
        var user_id = $("#user_id").val();

        $.ajax({
            url: '/admin/user/ajax-flush-user-pet',
            type: 'POST',
            dataType: 'json',
            data: {
                db_id: db_id,
                user_id: user_id
            },
            success: function (data) {
                alert( data.data );
            }
        });
    });
</script>

{include 'footer.tpl'}
