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
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->string('profile_org')->nullable()->after('security_code');
            $table->text('short_bio')->nullable()->after('profile_org');
            $table->string('current_position')->nullable()->after('short_bio');
            $table->string('field')->nullable()->after('current_position');
            $table->string('location')->nullable()->after('field');
            $table->text('about_text')->nullable()->after('location');
            $table->text('achievements')->nullable()->after('about_text');
            $table->text('timeline')->nullable()->after('achievements');
            $table->text('quote_text')->nullable()->after('timeline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->dropColumn([
                'profile_org',
                'short_bio',
                'current_position',
                'field',
                'location',
                'about_text',
                'achievements',
                'timeline',
                'quote_text',
            ]);
        });
    }
};
