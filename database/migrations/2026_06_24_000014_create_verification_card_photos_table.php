<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_card_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_card_id')->constrained('verification_cards')->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['verification_card_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_card_photos');
    }
};
