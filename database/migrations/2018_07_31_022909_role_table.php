<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',32)->default('')->comment('角色名称');
            $table->text('menu')->default('')->comment('menu');  
            $table->string('email',64)->default('')->comment('邮箱地址');
            $table->char('phone',11)->default('')->comment('手机号(仅支持大陆)');
            $table->text('app_id')->default('')->comment('WX APP ID');
            $table->string('open_id',16)->default('')->comment('WX OPEN ID');
            $table->unsignedTinyInteger('role')->default(0)->comment('0: 游客,1:普通用户,2: 风纪委员,3: admin.其余的为自定义');
            $table->unsignedTinyInteger('join_type')->default(0)->comment('0: 注册,1: activeCode加入');
            $table->unsignedTinyInteger('custom_account')->default(0)->comment('0: 未更改,1: 更改完毕');
            $table->nullableTimestamps('created_at')->comment('创建时间');
            $table->nullableTimestamps('updated_at')->comment('更新时间');
            $table->nullableTimestamps('last_login')->comment('上次登录');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('user');
    }
}
