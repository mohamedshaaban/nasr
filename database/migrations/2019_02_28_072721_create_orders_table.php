<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->references('id')->on('users');
            $table->unsignedInteger('address_id')->references('id')->on('user_address');
            $table->string('unique_id')->unique();
            $table->dateTime('order_date');
            $table->float('sub_total');
            $table->float('delivery_charges');
            $table->float('total');
            $table->integer('is_paid')->default(0);
            $table->integer('payment_method');
            $table->integer('shipping_method');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
