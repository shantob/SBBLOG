<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Friend;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function friends()
    {
        if (Friend::where('request_id', Auth::user()->id) || (Friend::where('recived_id', Auth::user()->id))) {
            return Friend::get();
        }
    }

    public function notifications()
    {
        $notifications = Notification::where('recived_id', Auth::user()->id)->get();
        return NotificationResource::collection($notifications);
    }
    public function notificationSeen(Request $request, $id)
    {
        try {
            $notification = Notification::find($id);
            $notification->update([
                'is_active' => 0
            ]);
            return response()->json([
                'status' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
