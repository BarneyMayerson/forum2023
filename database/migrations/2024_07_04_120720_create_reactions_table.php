<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("reactions", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->morphs("reactionable");
            $table
                ->boolean("is_like")
                ->default(true)
                ->comment("1 = like, 0 = dislike");
            $table->timestamps();
            $table->comment("For user reactions: likes and dislikes");

            $table->unique(
                ["user_id", "reactionable_type", "reactionable_id", "is_like"],
                "user_unique_reaction_index"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("reactions");
    }
};
