<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersFavouritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_favourites', function(Blueprint $table)
        {

           $table->increments('id');
           $table->integer('user_id')->unsigned();
           $table->integer('prop_id')->unsigned()->nullable();

           $table->timestamps();

        });	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('users_favourites');
	}

}
