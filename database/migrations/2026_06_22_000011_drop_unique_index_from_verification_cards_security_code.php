<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexExists = ! empty(DB::select(
            "SELECT 1 FROM information_schema.statistics
             WHERE table_schema = DATABASE()
               AND table_name = 'verification_cards'
               AND index_name = 'verification_cards_security_code_unique'
             LIMIT 1"
        ));

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
