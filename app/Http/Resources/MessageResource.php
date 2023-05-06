<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
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

        $day = ($houre) / 24;

        if (Carbon::now()->diffInMinutes($this->updated_at, true) < 60) {
            $time = Carbon::now()->diffInMinutes($this->updated_at, true) . ' minute ago';
        } elseif ((Carbon::now()->diffInMinutes($this->updated_at, true) > 60)&&($houre < 24)) {
            $time =  $houre . ' hour ago';
        } else {
            $time =  round($day) . ' day ago';
        };
        if ($this->request_id == Auth::user()->id) {
            $messageSender = 'me';
        }else{
            $messageSender = 'he';
        }
        return [
            'id' => $this->id,
            'creator' => Auth::user()->id,
            'request_id' => $this->request_id,
            'request_name' => $this->user->name,
            'messages' => $this->messages,
            'is_active' => $this->is_active,
            'diffInHours' =>  $time,
            'sender' =>  $messageSender,
        ];
    }
}
