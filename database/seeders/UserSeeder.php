<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@novelplatform.com',
            'email_verified_at' => now(),
            'password' => Hash::make('testtest'), // password
            'remember_token' => Str::random(10),
        ])->assignRole('Super Admin');

        $reader = User::create([
            'name' => 'TestReader',
            'email' => 'test@reader.com',
            'email_verified_at' => now(),
            'password' => Hash::make('testtest'), // password
            'remember_token' => Str::random(10),
        ])->assignRole('Reader');

        $translator = User::create([
            'name' => 'TestTranslator',
            'email' => 'test@translator.com',
            'email_verified_at' => now(),
            'password' => Hash::make('testtest'), // password
            'remember_token' => Str::random(10),
        ])->assignRole('Poster');

        if (config('app.env') != "production") {
            User::factory(10)->create()->each(function ($user) {
                if (rand(1,100) > 70) $user->assignRole('Poster');
                else $user->assignRole('Reader');
            });
        }
    }
}
