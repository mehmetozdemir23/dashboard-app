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
        // \App\Models\User::factory(10)->create();
        // $user = User::create([
        //     'id'=>1,
        //     'username'=>'mehmetozdemir',
        //     'email'=>'m.ozd23@gmail.com',
        //     'password'=>Hash::make('blablabla'),
        // ]);

        // $c1 = Container::factory()->create();
        // Grume::factory(50)->create(['container_number'=>$c1->number]);

        // $c2 = Container::factory()->create();
        // Grume::factory(50)->create(['container_number'=>$c2->number]);
            for ($i=1; $i < 20; $i++) {
                # code...
                Grume::factory()->create(['number'=>$i*100]);
            }


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
