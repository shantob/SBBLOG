<?php

namespace App\Http\Resources;

use App\Models\Friend;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SuggestFriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
            return [
                'id' => $this->id,
                'creator' => Auth::user()->id,
                'name' => $this->name,
                'image' => $this->profile?->image,
            ];
        
    }
}
