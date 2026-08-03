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
        Schema::create('advert_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('adverts')->onDelete('cascade');
            $table->date('date');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('contact_clicks')->default(0); // phone, email, whatsapp clicks
            $table->integer('unique_visitors')->default(0);
            $table->timestamps();

            // Ensure one record per advert per day
            $table->unique(['advert_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_analytics');
    }
};
