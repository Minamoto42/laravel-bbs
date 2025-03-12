<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // 需要清楚缓存, 否则会报错
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 先创建权限
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'edit_settings']);

        // 创建站长角色, 并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员角色, 并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // 需要清楚缓存, 否则会报错
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 从 config/permission.php 配置中获取所有表名
        $tableNames = config('permission.table_names');

        \App\Models\Model::unguard();
        \Illuminate\Support\Facades\DB::table($tableNames['role_has_permissions'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['model_has_roles'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['model_has_permissions'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['roles'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['permissions'])->delete();
    }
};
