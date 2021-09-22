<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchpersonIdToBatchOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('batchperson_id')->nullable();
            $table->foreign('batchperson_id')->references('id')->on('sales_persons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_orders', function (Blueprint $table) {
            //
        });
    }
}
