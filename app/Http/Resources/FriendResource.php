<?php

namespace App\Http\Resources;

use App\Models\Friend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $houre = round((Carbon::now()->diffInMinutes($this->updated_at, true)) / 60);

        $day = round($houre / 24);

        if (Carbon::now()->diffInMinutes($this->updated_at, true) < 60) {
            $time = Carbon::now()->diffInMinutes($this->updated_at, true) . ' minute ago';
        } elseif ((Carbon::now()->diffInMinutes($this->updated_at, true) > 60)&&($houre < 24)) {
            $time =  $houre . ' hour ago';
        } else {
            $time =  round($day). ' day ago';
        };
        $mitual = Friend::where('request_id', $this->request_id)->orwhere('recived_id', $this->request_id)->where('status', 0)->get();
        $mitual1 = Friend::where('request_id', Auth::user()->id)->orwhere('recived_id', Auth::user()->id)->where('status', 0)->get();
        if (count($mitual->intersect($mitual1)) > 0) {
            $data = count($mitual->intersect($mitual1));
        } else {
            $data = 0;
        }
        return [
            'id' => $this->id,
            'creator' => Auth::user()->id,
            'user_id' => $this->request_id,
            'request_name' => $this->user->name,
            'mitual_friend' => $data . ' Mitual Friend',
            'diffInHours' =>  $time,
        ];
    }
}
