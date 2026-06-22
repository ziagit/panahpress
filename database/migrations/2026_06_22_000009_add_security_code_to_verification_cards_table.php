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
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->string('security_code', 6)->nullable()->after('code');
        });

        $existingSecurityCodes = DB::table('verification_cards')
            ->whereNotNull('security_code')
            ->pluck('security_code')
            ->all();

        DB::table('verification_cards')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($cards) use (&$existingSecurityCodes) {
                foreach ($cards as $card) {
                    do {
                        $securityCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    } while (in_array($securityCode, $existingSecurityCodes, true));

                    $existingSecurityCodes[] = $securityCode;

                    DB::table('verification_cards')
                        ->where('id', $card->id)
                        ->update(['security_code' => $securityCode]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_cards', function (Blueprint $table) {
            $table->dropColumn('security_code');
        });
    }
};
