<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyFaqQuestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_faq_questions', function(Blueprint $table)
		{

           $table->increments('id');
           $table->text('text');
           $table->integer('public')->default(0);
           $table->integer('read')->default(0);

           $table->integer('user_id')->unsigned();
           $table->integer('prop_id')->unsigned()->nullable();

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
        Schema::drop('users_favourites');
	}

}