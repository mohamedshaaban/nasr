<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryChargesTypesSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            //For Vegatables
            $table->string('min_order')->nullable();
            $table->string('delivery_charges')->nullable();
            //For Palms
            $table->string('max_palms')->nullable();
            $table->string('delivery_charges_palms')->nullable();
            //For Sheeps
            $table->string('max_sheeps')->nullable();
            $table->string('delivery_charges_sheeps')->nullable();
            //For Cows
            $table->string('max_cows')->nullable();
            $table->string('delivery_charges_cows')->nullable();            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
}
