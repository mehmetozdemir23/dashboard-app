<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Grume;
use App\Models\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => 1,
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $c1 = Container::factory()->create();
        Grume::factory(50)->create(['container_number' => $c1->number]);

        $c2 = Container::factory()->create();
        Grume::factory(50)->create(['container_number' => $c2->number]);

    }
}
