<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\RoleMaster;
use App\Models\UserRolesMapping;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), // Mật khẩu giả
        ]);

        // Tạo vai trò Admin
        $adminRole = RoleMaster::firstOrCreate([
            'rolename' => 'Admin'
        ]);

        // Gán vai trò Admin cho tài khoản
        UserRolesMapping::create([
            'userid' => $admin->id,
            'roleid' => $adminRole->id,
        ]);
    }
}
