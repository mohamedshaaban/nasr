<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->references('id')->on('vendors');
            $table->unsignedInteger('area_id')->references('id')->on('areas');
            $table->text('delivery_charge')->nullable();
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
        Schema::dropIfExists('vendor_areas', function (Blueprint $table) {
            //
        });
    }
}
