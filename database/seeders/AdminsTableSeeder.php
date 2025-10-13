<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');

        $admin = new Admin;
        $admin->name = 'Admin';
        $admin->role = 'admin';
        $admin->mobile = '1234567890';
        $admin->email = 'admin@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'Wilfried Gnahore';
        $admin->role = 'subadmin';
        $admin->mobile = '1234567892';
        $admin->email = 'admin2@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'Emliss Gnahore';
        $admin->role = 'subadmin';
        $admin->mobile = '1234567891';
        $admin->email = 'admin3@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'Emliss Gnahore';
        $admin->role = 'subadmin';
        $admin->mobile = '1234567892';
        $admin->email = 'admin4@admin.com';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();
    }
}
