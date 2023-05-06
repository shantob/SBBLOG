<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function messages()
    {
        $messages = Message::where('request_id', Auth::user()->id)->orWhere('recived_id', Auth::user()->id)->get();
        return MessageResource::collection($messages);
    }

    public function messagesById($id)
    {
        $permition = Message::where('request_id', Auth::user()->id)->where('recived_id', $id)->get();
        $permition1 = Message::where('request_id', $id)->where('recived_id', Auth::user()->id)->get();
        if ($permition || $permition1) {
            //Message::where('request_id', $id)->orWhere('recived_id', $id)->get();
            return MessageResource::collection($permition1);
            // return response()->json([
            //     'data' => $permition,
            //     'status' => true,
            // ]);
        } else {
            $user = User::find($id);
            return response()->json([
                'data' => $user,
                'message' => 'Starting Chatting with your first massage',
                'status' => true,
            ]);
        }
    }

    public function messageStore(Request $request)
    {
        try {
            Message::create([
                'request_id' => Auth::user()->id,
                'recived_id' => $request->user_id,
                'messages' => $request->messages,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Message Send',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function messageSeen(Request $request, $id)
    {
        $messageSeen = Message::find($id);
        try {
            $messageSeen->update([
                'is_active' => 0,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Message Seen',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
