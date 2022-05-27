<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('qwerty123')
        ]);

        Customer::create([
            'user_id' => $user->id,
            'first_name' => 'admin_name',
            'last_name' => 'admin_adminuch',
            'role' => Customer::ROLE_ADMIN
        ]);


    }
}
