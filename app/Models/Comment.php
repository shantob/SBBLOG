<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function posts()
    {
        return $this->belongsTo(Post::class, 'posts');
    }
    public function comment_replies()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
