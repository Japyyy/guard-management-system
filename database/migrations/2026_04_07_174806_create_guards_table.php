<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('civil_status');
            $table->date('birthdate');
            $table->date('date_hired');
            $table->string('address');
            $table->string('license_number')->unique();
            $table->date('license_validity_date');

            $table->string('sss_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('tin_number')->nullable();
            $table->date('nbi_clearance_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guards');
    }
};