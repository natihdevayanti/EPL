<?php

use App\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::create([
            'name' => 'Superadmin'
        ]);
        UserType::create([
            'name' => 'Admin Pembayaran'
        ]);
        UserType::create([
            'name' => 'Admin Produksi'
        ]);
        //
    }
}
