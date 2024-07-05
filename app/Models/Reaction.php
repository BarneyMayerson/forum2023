<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    const LIKE = 1;
    const DISLIKE = 0;
    const POSITIVE = true;
    const NEGATIVE = false;

    protected $casts = [
        "is_like" => "boolean",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function toggle(): self
    {
        $this->is_like = !$this->is_like;

        return $this;
    }
}
