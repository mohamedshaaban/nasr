<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo_ar');
            $table->string('logo_en');
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('google_store_link')->nullable();
            $table->string('app_store_link')->nullable();
            $table->string('copy_right_ar');
            $table->string('copy_right_en');
            $table->string('address_ar');
            $table->string('address_en');
            $table->string('phone');
            $table->string('email_support')->nullable();
            $table->string('email_info')->nullable();
            $table->unsignedInteger('default_currency')->default(0);
            $table->text('working_hours')->nullable();
            $table->text('new_arrival_days')->nullable();
            $table->timestamps();
            $table->foreign('default_currency')->references('id')->on('currencies');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings', function (Blueprint $table) {
            //
        });
    }
}
