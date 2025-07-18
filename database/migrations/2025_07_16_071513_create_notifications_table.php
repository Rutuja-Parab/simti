<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->tinyInteger('term')->nullable()->after('subject_id')->comment('1=Term-1, 2=Term-2, null=single term');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   
};
