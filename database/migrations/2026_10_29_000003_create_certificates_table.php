<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('verification_token', 64)->unique();
            $table->string('recipient_name');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('issuer')->default('Panah Press');
            $table->string('image_path');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
