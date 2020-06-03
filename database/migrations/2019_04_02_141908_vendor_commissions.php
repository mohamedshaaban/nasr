<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorCommissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_commissions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->integer('fixed')->nullable();
            $table->integer('precentage')->default(0);
            $table->timestamps();
             $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_commissions');
    }
}
