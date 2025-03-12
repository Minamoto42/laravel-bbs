<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 生成 10 个用户
        User::factory()->count(10)->create();

        // 单独处理第一个用户的数据, 以供我们方便测试使用
        $user = User::find(1);
        $user->name = 'Minamoto';
        $user->email = 'ryugenrokuroku@gmail.com';
        $user->password = bcrypt('11111111');
        $user->avatar = config('app.url') . '/uploads/images/default-avatars/600.jpg';
        $user->save();

        // 初始化用户角色, 将 ID 为 1 的用户指定为站长
        $user->assignRole('Founder');

        // 将 ID 为 2 的用户指定为管理员
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
