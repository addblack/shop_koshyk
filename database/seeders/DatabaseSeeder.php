<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Адмін
        User::updateOrCreate(
            ['email' => 'admin@freshmart.ua'],
            [
                'name'     => 'Адміністратор',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $this->call(ShopSeeder::class);
    }
}
