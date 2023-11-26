<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id');
            $table->unsignedBigInteger('prj_id');
            $table->foreign('prj_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('entered_by');
            $table->foreign('entered_by')->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('working_on');
            $table->string('hour');
            $table->string('overtime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_sheets');
    }
}
