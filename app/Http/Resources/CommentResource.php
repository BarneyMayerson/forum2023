<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class CommentResource extends JsonResource
{
    private bool $withLikePermission = false;

    public function withLikePermission(): self
    {
        $this->withLikePermission = true;

        return $this;
    }

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
            "post" => PostResource::make($this->whenLoaded("post")),
            "body" => $this->body,
            "html" => $this->html,
            "likes_count" => Number::abbreviate($this->likes_count),
            "dislikes_count" => Number::abbreviate($this->dislikes_count),
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at,
            "can" => [
                "update" => $request->user()?->can("update", $this->resource),
                "delete" => $request->user()?->can("delete", $this->resource),
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
