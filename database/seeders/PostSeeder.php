<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Post::create([
                'posted_by'=>$user->id,
                'title'=>fake()->sentence(5),
                'description'=>fake()->text(30),
                'image' => fake()->imageUrl($width=400, $height=400),
                'like'=>fake()->numberBetween(0, 1000),
                'tags'=>fake()->sentence(5),
            ]);
        }
    }
}
