<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorOrderDeliveryChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_order_delivery_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->references('id')->on('vendors');
            $table->unsignedInteger('order_id')->references('id')->on('orders');
            $table->unsignedInteger('area_id')->references('id')->on('areas')->nullable();
            $table->float('delivery_charges');
            $table->float('total');
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
        Schema::dropIfExists('vendor_order_delivery_charges', function (Blueprint $table) {
            //
        });
    }
}
