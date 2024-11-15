<?php

// database/seeders/usersTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class usersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Use Faker to generate random data

        // Insert 50 records into the 'users' table
        foreach (range(1, 500) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // Set a default password
                'created_at' => now(),  // Use current timestamp for created_at
                'updated_at' => now(),  // You may also want to set updated_at
            ]);
        }
    }
}

