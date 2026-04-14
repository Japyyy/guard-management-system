<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guards', function (Blueprint $table) {
            $table->boolean('notified_60_days')->default(false)->after('nbi_clearance_date');
            $table->boolean('notified_30_days')->default(false)->after('notified_60_days');
        });
    }

    public function down(): void
    {
        Schema::table('guards', function (Blueprint $table) {
            $table->dropColumn(['notified_60_days', 'notified_30_days']);
        });
    }
};