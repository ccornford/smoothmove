<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyFaqAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_faq_answers', function(Blueprint $table)
		{

           $table->increments('id');
           $table->text('text');
           $table->integer('read')->default(0);


           $table->integer('question_id')->unsigned()->nullable();

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
