<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('brand_name');
            $table->string('generic_name')->nullable();
            $table->string('description')->nullable();
            $table->string('indication')->nullable();
            $table->string('indikasi')->nullable();
            $table->string('instruction')->nullable();
            $table->date('expiry_date')->nullable();
            $table->double('purchase_price')->nullable();
            $table->string('purchase_uom')->nullable();
            $table->integer('purchase_quantity')->nullable();
            $table->integer('stock_level')->nullable();
            $table->double('selling_price')->nullable();
            $table->string('selling_uom')->nullable();
            $table->integer('reorder_quantity')->nullable();
            $table->string('reorder_supplier')->nullable();
            $table->foreignId('tariff_id')->constrained()->nullable();
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
        Schema::dropIfExists('items');
    }
}
