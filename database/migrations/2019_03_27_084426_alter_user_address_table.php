<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_address', function (Blueprint $table) {
            $table->text('block')->nullable();
            $table->text('street')->nullable();
            $table->text('avenue')->nullable();
            $table->text('floor')->nullable();
            $table->text('flat')->nullable();
            $table->text('extra_direction')->nullable();
            $table->text('governate_id')->nullable();
            $table->text('area_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_address', function (Blueprint $table) {
            //
        });
    }
}
