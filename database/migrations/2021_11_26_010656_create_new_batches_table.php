<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no');
            $table->unsignedBigInteger('batch_person');
            $table->foreign('batch_person')->references('id')->on('sales_persons');
            $table->string('batch_status');
            $table->unsignedBigInteger('tariff');
            $table->foreign('tariff')->references('id')->on('tariffs');
            $table->string('patient_status');
            $table->string('submission_date')->nullable();
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
        Schema::dropIfExists('new_batches');
    }
}
