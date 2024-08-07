<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMarkdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use ConvertsMarkdownToHtml;
    use HasFactory;
    use hasReactions;
    use Searchable;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function title(): Attribute
    {
        return Attribute::get(fn($value) => Str::title($value));
    }

    public function showRoute(array $parameters = []): string
    {
        return route("posts.show", [
            $this,
            Str::slug($this->title),
            ...$parameters,
        ]);
    }
}
