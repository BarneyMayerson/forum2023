<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

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
            "id" => $this->id,
            "user" => UserResource::make($this->whenLoaded("user")),
            "topic" => TopicResource::make($this->whenLoaded("topic")),
            "title" => $this->title,
            "body" => $this->body,
            "html" => $this->html,
            "likes_count" => Number::abbreviate($this->likes_count),
            "dislikes_count" => Number::abbreviate($this->dislikes_count),
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
            "routes" => [
                "show" => $this->showRoute(),
            ],
            "reaction" => [
                "exists" => $this->reactions()
                    ->where("user_id", $request->user()?->id)
                    ->exists(),
                "is_like" => $this->likes()
                    ->where("user_id", $request->user()?->id)
                    ->exists(),
            ],
        ];
    }
}
