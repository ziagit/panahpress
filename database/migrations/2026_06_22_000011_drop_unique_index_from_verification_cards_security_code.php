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
        $indexExists = Schema::hasIndex('verification_cards', 'verification_cards_security_code_unique');

        if ($indexExists) {
            Schema::table('verification_cards', function (Blueprint $table) {
                $table->dropUnique('verification_cards_security_code_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->unique('security_code');
        });
    }
};
