<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images = $this->image;
        if ($images == null) {
            $profiles =  'profiles/images/default.jpg';
        }
       // $imagePatth = url('/storage/' . $profiles);
        return [
            'id' => $this->id,
            'creator' => Auth::user()->id,
            'user_id' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'image' => $images,
        ];
    }
}
