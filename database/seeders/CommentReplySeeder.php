<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            $user = User::inRandomOrder()->first();
            CommentReply::create([
                'comment_id' => $comment->id,
                'replied_by' => $user->id,
                'reply' => fake()->text(30),
            ]);
        }
    }
}
