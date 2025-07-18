<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->foreignId('course_detail_id')->nullable()->constrained('course_details')->onDelete('set null');
            $table->dropColumn(['course', 'batch_no']);
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('course')->nullable();
            $table->string('batch_no')->nullable();
            $table->dropForeign(['course_detail_id']);
            $table->dropColumn('course_detail_id');
        });
    }
}; 