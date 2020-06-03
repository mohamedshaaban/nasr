<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVendorsTableAddActivatecodeParentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->text('logo')->nullable();
            $table->text('activatecode')->nullable();
             $table->text('name_ar')->nullable();
            $table->text('overview_en')->nullable();
            $table->text('overview_ar')->nullable();
            $table->integer('is_active')->default(0);
            $table->unsignedInteger('parent_id')->references('id')->on('vendors')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
}
