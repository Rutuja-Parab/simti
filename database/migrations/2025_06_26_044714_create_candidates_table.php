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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('roll_no');
            $table->string('name');
            $table->date('dob');
            $table->string('indos_no');
            $table->string('passport_no');
            $table->string('cdc_no');
            $table->string('dgs_certificate_no')->nullable();
            $table->string('course');
            $table->string('photo_path');
            $table->string('signature_path');
            $table->string('passport_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
