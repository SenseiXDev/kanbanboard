<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Utility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('1234'),
                'type' => 'admin',
                'lang' => 'en',
                'is_active' => '1',
                'email_verified_at' => date("H:i:s"),
            ]
        );

        // create new email template notification
        $admin->defaultEmail();

        $owner = User::create(
            [
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('1234'),
                'type' => 'owner',
                'lang' => 'en',
                'created_by' => $admin->id,
                'is_active' => '1',
                'email_verified_at' => date("H:i:s"),
            ]
        );

        $owner->assignPlan(1);

        Utility::languagecreate();
    }
}
