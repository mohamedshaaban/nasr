<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableVendorOrderDeliveryChargesAddCommission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_order_delivery_charges', function (Blueprint $table) {
            $table->float('commission_kd');
            $table->float('commission_percentage');
            $table->integer('transferred')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_order_delivery_charges', function (Blueprint $table) {
            //
        });
    }
}
