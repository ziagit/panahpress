<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verification_cards', function (Blueprint $table) {
            if (Schema::hasColumn('verification_cards', 'galary_id')) {
                $table->dropColumn('galary_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->string('galary_id')->nullable()->after('languages');
        });
    }
};
