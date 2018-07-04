<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->delete();
        for($i = 0;$i < 10;$i++) {
            User::create([
                'name' => 'username',
                'email' => mt_rand(10,1000) . '@qq.com',
                'password' => 'password' . $i
                // 'created_at' => time(),
                // 'updated_at' => '0000-00-00 00:00:00'    
            ]);
        }
    }
}

