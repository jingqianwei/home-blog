<?php

use App\Models\User;
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
        factory(App\Models\User::class, 50)->create(); //向users表中插入50条模拟数据
        $user = User::find(1); //插入完后，找到 id 为 1 的用户
        $user->name = "我很牛逼"; //设置 用户名
        $user->email = "1007123591@qq.com"; //设置 邮箱
        $user->password = bcrypt('jqw'); //设置 密码
        $user->save(); //保存
    }
}
