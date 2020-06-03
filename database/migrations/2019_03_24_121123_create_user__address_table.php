<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address_type');
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('fax')->nullable();
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->string('zip_code')->nullable();
            $table->unsignedBigInteger('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('country_id')->references('id')->on('countries');
            $table->integer('is_default')->default(0);

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
        Schema::table('user_address', function (Blueprint $table) {
            //
        });
    }
}
