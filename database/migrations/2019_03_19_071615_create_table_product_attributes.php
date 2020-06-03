<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTableProductAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->references('id')->on('products')->nullable();
            $table->unsignedInteger('sizes_id')->references('id')->on('sizes')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('product_breeds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->references('id')->on('products')->nullable();
            $table->unsignedInteger('breeds_id')->references('id')->on('breeds')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('product_genders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->references('id')->on('products')->nullable();
            $table->unsignedInteger('genders_id')->references('id')->on('genders')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('product_ages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->references('id')->on('products')->nullable();
            $table->unsignedInteger('ages_id')->references('id')->on('ages')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('product_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->references('id')->on('products')->nullable();
            $table->unsignedInteger('colors_id')->references('id')->on('colors')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('product_sizes', function (Blueprint $table) {
            //
        });
        Schema::dropIfExists('product_breeds', function (Blueprint $table) {
            //
        });
        Schema::dropIfExists('product_genders', function (Blueprint $table) {
            //
        });
        Schema::dropIfExists('product_ages', function (Blueprint $table) {
            //
        });
        Schema::dropIfExists('product_colors', function (Blueprint $table) {
            //
        });

    }
}
