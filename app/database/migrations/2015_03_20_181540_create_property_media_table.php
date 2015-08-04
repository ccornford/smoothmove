<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('property_media', function(Blueprint $table)
        {

           $table->increments('id');
           $table->string('media_file_name')->nullable();
           $table->integer('media_file_size')->nullable();
           $table->string('media_content_type')->nullable();
           $table->timestamp('media_updated_at')->nullable();


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
        Schema::drop('property_media');
	}

}
