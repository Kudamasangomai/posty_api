<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'post_id'=> $this->id,
            'post'=>substr($this->post, 0, 50) . '...',
            'created' => $this->created_at->diffForHumans(),
            'createdBy'=>new UserResource($this->user),
            // 'created_by'=> $this->user_id,
           
            
        ];
    }
}
