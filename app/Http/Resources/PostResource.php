<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Http\Resources\Resource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
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
        $month = ($day) / 30;

        if (Carbon::now()->diffInMinutes($this->updated_at, true) < 60) {
            $time = Carbon::now()->diffInMinutes($this->updated_at, true) . ' minute ago';
        } elseif ((Carbon::now()->diffInMinutes($this->updated_at, true) > 60)&&($houre < 24)) {
            $time =  $houre . ' hour ago';
        } else if(($houre > 24)&&($day <30)){
            $time = round($day) . ' day ago';
        }else{
            $time = round($month) . ' month ago';
        };
        return [
            'id' => $this->id,
            'creator' => Auth::user()->id,
            'user_id' => $this->user->name,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'like' => $this->like,
            'comments' => count($this->comments),
            'diffInHours' =>  $time,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
