<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all();
        foreach ($posts as $post) {
            $user_id = User::inRandomOrder()->first();
            Comment::create([
                'post_id' => $post->id,
                'commented_by' => $user_id,
                'comment' => fake()->text(30),
            ]);
        }
    }
}
