<?php

use App\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',32)->default('')->comment('用户名称');
            $table->string('password',255)->default('')->comment('密码：md5格式');  
            $table->string('email',64)->default('')->comment('邮箱地址');
            $table->char('phone',11)->default('')->comment('手机号(仅支持大陆)');
            $table->string('ip',16)->default('')->comment('IP');
            $table->text('app_id')->nullable()->comment('WX APP ID');
            $table->string('open_id',16)->default('')->comment('WX OPEN ID');
            $table->unsignedTinyInteger('role')->default(0)->comment('0: 游客,1:普通用户,2: 风纪委员,3: admin.其余的为自定义');
            $table->unsignedTinyInteger('join_type')->default(0)->comment('0: 注册,1: activeCode加入');
            $table->unsignedTinyInteger('custom_account')->default(0)->comment('0: 未更改,1: 更改完毕');
            $table->timestamp('last_login')->comment('上次登录');
			$table->timestamps();
        });
        // 默认值
        $user_ary = [
            'username' => 'Snail',
            'password' => '$2y$10$gCCqQV87tRGdgKvAJ8D/b.ZerGjH/q2DxJCBb4mkcOiAAs.D2hJiq',
            'email' => '892333187@qq.com',
            'phone' => '15366196173',
            'role' => '3',
            'custom_account' => '1'
        ];
        $user = new User;
        $user->fill($user_ary);
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
