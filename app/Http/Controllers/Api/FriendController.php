<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use App\Http\Resources\SuggestFriendResource;
use App\Models\Friend;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function allFriend()
    {
        $requestedFriend = Friend::where('request_id', Auth::user()->id)->orWhere('recived_id', Auth::user()->id)->where('status', 0)->get();
        return FriendResource::collection($requestedFriend);
    }
    public function suggestFriend()
    {

        $recived_id = Friend::where('request_id', Auth::user()->id)->where('status', 0)->get('recived_id');

        $request_id = Friend::Where('recived_id', Auth::user()->id)->where('status', 0)->get('request_id');

        $suggestFriend = User::where('id', '!=', $recived_id)->orWhere('id', '!=', $request_id)->Where('id', '!=', Auth::user()->id)->get();

        return SuggestFriendResource::collection($suggestFriend);
    }
    public function FriendRequest(Request $request)
    {
        if (Friend::where('request_id', Auth::user()->id)->where('recived_id', $request->user_id)->where('status', '!=', NULL)) {
            return response()->json([
                'status' => false,
                'message' => 'Something Wrong !! ',
            ], 500);
        }
        try {
            Friend::create([
                'request_id' => Auth::user()->id,
                'recived_id' => $request->user_id,
                'status' => 2
            ]);
            Notification::create([
                'request_id' => Auth::user()->id,
                'recived_id' => $request->user_id,
                'notification' => 'Sent a friend Request',
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Friend Request Send Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function confirmRequest(Request $request, $id)
    {
        try {
            $friendConfirm = Friend::find($id);
            $friendConfirm->update([
                'status' => 0
            ]);
            Notification::create([
                'request_id' => $request->user_id,
                'recived_id' => Auth::user()->id,
                'notification' => 'Accept Your Friend Request',
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Friend Request Accept Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function showRequest()
    {
        $requestedFriend = Friend::where('recived_id', Auth::user()->id)->where('status', 2)->get();
        return FriendResource::collection($requestedFriend);
    }
    public function test()
    {
        $mitual = Friend::where('request_id', 3)->orwhere('recived_id', 3)->where('status', 0)->get();
        $mitual1 = Friend::where('request_id', Auth::user()->id)->orwhere('recived_id', Auth::user()->id)->where('status', 0)->get();
        if (count($mitual->intersect($mitual1)) > 0) {
            $data = count($mitual->intersect($mitual1));
        } else {
            $data = 0;
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
