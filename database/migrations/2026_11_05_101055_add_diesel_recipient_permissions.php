<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // إضافة صلاحيات إدارة الجهات المستلمة
        $permissions = [
            ['name' => 'diesel_recipient-read', 'guard_name' => 'web'],
            ['name' => 'diesel_recipient-create', 'guard_name' => 'web'],
            ['name' => 'diesel_recipient-update', 'guard_name' => 'web'],
            ['name' => 'diesel_recipient-delete', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert($permission);
        }

        // إضافة الصلاحيات لدور Super-User
        $superUserRole = DB::table('roles')->where('name', 'Super-User')->first();
        if ($superUserRole) {
            foreach ($permissions as $permission) {
                $permissionId = DB::table('permissions')->where('name', $permission['name'])->first()->id;
                DB::table('role_has_permissions')->insert([
                    'role_id' => $superUserRole->id,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('role_has_permissions')->whereIn('permission_id', function ($query) {
            $query->select('id')->from('permissions')->whereIn('name', [
                'diesel_recipient-read',
                'diesel_recipient-create',
                'diesel_recipient-update',
                'diesel_recipient-delete',
            ]);
        })->delete();

        DB::table('permissions')->whereIn('name', [
            'diesel_recipient-read',
            'diesel_recipient-create',
            'diesel_recipient-update',
            'diesel_recipient-delete',
        ])->delete();
    }
};
