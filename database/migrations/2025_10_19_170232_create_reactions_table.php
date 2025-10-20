<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reactable'); // reactable_id and reactable_type (polymorphic)
            $table->string('type'); // like, love, laugh, wow, sad, angry
            $table->timestamps();

            // User can only react once per item with one type
            $table->unique(['user_id', 'reactable_id', 'reactable_type']);
            $table->index(['reactable_id', 'reactable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
