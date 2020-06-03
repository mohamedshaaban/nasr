<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_track', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->references('id')->on('order')->nullable();
            $table->unsignedInteger('order_status_id')->references('id')->on('order_status')->nullable();
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
        Schema::table('order_tack', function (Blueprint $table) {
            //
        });
    }
}
