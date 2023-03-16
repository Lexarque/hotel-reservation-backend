<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'receptionist', 'verified-guest'];

        foreach($roles as $role) {
            User::create([
                'name' => $role,
                'email' => $role . '@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => Role::where('name', $role)->first()->id
            ]);
        }
    }
}
