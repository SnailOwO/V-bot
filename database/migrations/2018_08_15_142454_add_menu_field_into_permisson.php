<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuFieldIntoPermisson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_show')->comment('0:hide 1:show')->default(1)->after('guard_name');
            $table->integer('pid')->comment('parent id')->default(0)->after('guard_name');
            $table->integer('level')->default(0)->after('guard_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('is_show');
            $table->dropColumn('pid');
            $table->dropColumn('level');
        });
    }
}
