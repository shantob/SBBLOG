<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Friend;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function post()
    {
        return PostResource::collection(Post::with('comments', 'comments.comment_replies')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'posted_by' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable',
            'tags' => 'nullable',
        ]);
        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $fileName = date('y-m-d') . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(storage_path('app/public/posts'), $fileName);
                $data['image'] = '/public/posts/' . $fileName;
            }
            Post::create($data);
            $post = Friend::where('status', 0)->get();
            if (Friend::where('recived_id', Auth::user()->id)) {
                $post = Friend::where('recived_id', Auth::user()->id)->get('request_id');
            }
            if (Friend::orWhere('request_id', Auth::user()->id)) {
                $post = Friend::orWhere('request_id', Auth::user()->id)->get('recived_id');
            }
            if (Friend::where('recived_id', $data['posted_by'])) {
                $post = Friend::orWhere('status', 2)->get('request_id');
            }
            foreach ($post as $posted_by) {
                Notification::create([
                    'request_id' => Auth::user()->id,
                    'recived_id' =>  $posted_by->request_id,
                    'notification' => 'Add a New Post',
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Your Post Added Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function postEdit($id)
    {
        $post = Post::find($id);
        return response()->json([
            'status' => true,
            'data' => $post,
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'tags' => $request->tags,
            ];
            if ($request->hasFile('image')) {
                $fileName = date('y-m-d') . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(storage_path('app/public/posts'), $fileName);
                $data['image'] = '/public/posts/' . $fileName;
            }
            $post_id = Post::find($id);
            $post_id->update($data);
            return response()->json([
                'data' => $data,
                'status' => true,
                'message' => 'Your Post Updated Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function commentsStore(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'commented_by' => 'required|exists:users,id',
            'comment' => 'required',
        ]);
        try {
            Comment::create([
                'post_id' => $request->post_id,
                'commented_by' => $request->commented_by,
                'comment' => $request->comment,
            ]);
            $posted = Post::where('id', $request->post_id)->select('posted_by')->get();
            foreach ($posted as $posted_by) {
                Notification::create([
                    'request_id' => Auth::user()->id,
                    'recived_id' =>  $posted_by->posted_by,
                    'notification' => 'Comment On Your Post',
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Posted your Comment Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function commentReplyStore(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'replied_by' => 'required|exists:users,id',
            'reply' => 'required',
        ]);
        try {
            CommentReply::create([
                'comment_id' => $request->comment_id,
                'replied_by' => $request->replied_by,
                'reply' => $request->reply,
            ]);
            $reply = Comment::where('id', $request->comment_id)->select('commented_by')->get();
            foreach ($reply as $replies_by) {
                Notification::create([
                    'request_id' => Auth::user()->id,
                    'recived_id' =>  $replies_by->commented_by,
                    'notification' => 'Replied On Your Comment',
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Reply Added Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function postDelete($id)
    {
        $post = Post::find($id);
        foreach ($post->comments as $comment) {
                foreach ($comment->comment_replies as $comment_reply) {
                    $comment_reply->delete();
                }
                $comment->delete();
            }

        $post->delete();
        return response()->json([
            'status' => true,
            'message' => 'Post Delete Successfully',
        ]);
    }
}
// $posts = Post::with('comments', 'comments.comment_replies')->get();
// // return response()->json([
// //     'status' => true,
// //     'data' => $posts,
// // ]);
// $posts = Post::get();
// $posts->load('comments', 'comments.comment_replies');
// //return PostResource::collection($posts);

// // testing
// foreach ($posts as $key => $property) {
//     $data['items'][$key] = [
//         'id' => $property->id,
//         'user_id' => $property->user->name,
//         'title' => $property->title,
//         'description' => $property->description,
//         'image' => $property->image,
//         'like' => $property->like,
//         'comments' => count($property->comments),
//         'diffInHours' =>  $property->updated_at,
//     ];
//     foreach ($property->comments as $comment) {
//         $data['items'][$key]['comment'][] = [
//             'commented_by' => $comment->commented_by,
//         ];
//     }
// }
// // return response()->json([
// //     'status' => true,
// //     'data' => $data,
// // ]);