<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activities;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create()->each(function ($user) {
            Activities::factory(rand(5, 15))->create([
                'user_id' => $user->id,
                'activity_date' => now()->subDays(rand(0, 30))
            ]);
            $user->total_points = $user->activities()->sum('points');
            $user->save();
        });
    }
}
