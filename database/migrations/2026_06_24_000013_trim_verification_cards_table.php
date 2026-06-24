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
            $table->string('phone')->nullable()->after('full_name');
            $table->string('email')->nullable()->after('phone');
            $table->text('languages')->nullable()->after('expertise');
            $table->string('galary_id')->nullable()->after('languages');

            $table->dropColumn([
                'profile_org',
                'field',
                'about_text',
                'achievements',
                'timeline',
                'quote_text',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->string('profile_org')->nullable()->after('security_code');
            $table->string('field')->nullable()->after('current_position');
            $table->text('about_text')->nullable()->after('location');
            $table->text('achievements')->nullable()->after('about_text');
            $table->text('timeline')->nullable()->after('achievements');
            $table->text('quote_text')->nullable()->after('timeline');
            $table->dropColumn(['phone', 'email', 'languages', 'galary_id']);
        });
    }
};
