<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Drop foreign key first
        $table->dropForeign(['course_id']);

        // Then drop the column
        $table->dropColumn('course_id');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null');
    });
}
};
