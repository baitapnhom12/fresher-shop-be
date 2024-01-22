<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Faker\Factory as Faker;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        User::create([
            'name' => $faker->firstName,
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin@123'),
            'role' => UserRole::Administrator,
            'birthday' => $faker->dateTime,
            'phone' => $faker->phoneNumber,
        ]);

        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('12345678'),
                'role' => UserRole::User,
                'birthday' => $faker->dateTime,
                'phone' => $faker->phoneNumber,
            ]);
        }
    }
}
