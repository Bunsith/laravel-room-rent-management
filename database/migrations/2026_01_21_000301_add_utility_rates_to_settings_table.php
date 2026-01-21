<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('water_rate', 12, 2)->default(0.75)->after('deposit_default');
            $table->decimal('electric_rate', 12, 2)->default(0.25)->after('water_rate');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['water_rate', 'electric_rate']);
        });
    }
};
