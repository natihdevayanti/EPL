<?php

use App\User;
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
        User::create([
            'name' => 'Admin Ecomm',
            'email' => 'admin@ecomm.id',
            'password' => 'asdasdasd',
            'user_type_id' => 1
        ]);
        //
    }
}
