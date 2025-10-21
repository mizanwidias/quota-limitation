<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // contoh data seeder langsung di sini
        User::create([
            'cust_id'   => 'cust1',
            'cust_name' => 'Mak Enok',
            'no_hp'     => '081234567890',
            'password'  => Hash::make('password123'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust2',
            'cust_name' => 'Umi Aghis',
            'no_hp'     => '082345678901',
            'password'  => Hash::make('rahasia456'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust3',
            'cust_name' => 'Teh Ida',
            'no_hp'     => '083456789012',
            'password'  => Hash::make('teh12345
            '),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust4',
            'cust_name' => 'Pak Mus',
            'no_hp'     => '084567890123',
            'password'  => Hash::make('mus12345'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust5',
            'cust_name' => 'Pak Muts',
            'no_hp'     => '085678901234',
            'password'  => Hash::make('muts6789'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust6',
            'cust_name' => 'Pak Rifai',
            'no_hp'     => '085678901235',
            'password'  => Hash::make('rifai6789'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust7',
            'cust_name' => 'Pak Rafi',
            'no_hp'     => '086789012346',
            'password'  => Hash::make('rafi7890'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust9',
            'cust_name' => 'Bu Uwa',
            'no_hp'     => '088901234567',
            'password'  => Hash::make('uwa8901'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust10',
            'cust_name' => 'Umi',
            'no_hp'     => '089012345678',
            'password'  => Hash::make('umi9012'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust13',
            'cust_name' => 'Mamah Juna',
            'no_hp'     => '081345678901',
            'password'  => Hash::make('juna1234'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust16',
            'cust_name' => 'Mamah Fahmi',
            'no_hp'     => '081456789012',
            'password'  => Hash::make('fahmi1234'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'cust17',
            'cust_name' => 'Kang Ecep',
            'no_hp'     => '081567890123',
            'password'  => Hash::make('ecep1234'),
            'role'      => 'customer',
        ]);

        User::create([
            'cust_id'   => 'admin1',
            'cust_name' => 'Admin Satu',
            'no_hp'     => '083456789012',
            'password'  => Hash::make('adminpass'),
            'role'      => 'administrasi',
        ]);

        User::create([
            'cust_id'   => 'owner1',
            'cust_name' => 'Pemilik Toko',
            'no_hp'     => '084567890123',
            'password'  => Hash::make('ownerpass'),
            'role'      => 'pemilik',
        ]);
    }
}
