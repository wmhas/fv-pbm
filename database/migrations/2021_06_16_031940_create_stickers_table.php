<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stickers', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->string('item_name');
            $table->integer('quantity');
            $table->string('ic_no');
            $table->date('dispensing_date');
            $table->string('instruction');
            $table->integer('dose_quantity');
            $table->integer('frequency');
            $table->string('dose_uom');
            $table->string('indikasi');
            $table->string('salutations');
            $table->string('p1')->nullable();
            $table->integer('p2')->nullable();
            $table->string('p3')->nullable();
            $table->string('p4')->nullable();
            $table->integer('p5')->nullable();
            $table->string('p6')->nullable();
            $table->integer('p7')->nullable();
            $table->string('p8')->nullable();
            $table->string('p9')->nullable();
            $table->integer('p10')->nullable();
            $table->string('p11')->nullable();
            $table->string('p12')->nullable();
            $table->string('p13')->nullable();
            $table->string('p14')->nullable();
            $table->string('p15')->nullable();
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
        Schema::dropIfExists('stickers');
    }
}
