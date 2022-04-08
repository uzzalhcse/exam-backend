<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $roles = Auth::guard('customer')->check() ? ['customer'] : ['admin'];
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'roles'=> $roles,
        ];
    }
}
