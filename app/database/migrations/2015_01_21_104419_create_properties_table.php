<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function(Blueprint $table)
        {
           $table->increments('id');

           $table->integer('price')->default(0);
           $table->integer('bedrooms')->default(1);
           $table->integer('bathrooms')->default(1);
           $table->text('description');
           $table->integer('view_count')->default(0);
           $table->boolean('let_agreed')->default(0);
           $table->timestamp('listing_date')->default(DB::raw('CURRENT_TIMESTAMP'));

           $table->string('street', 128);
           $table->string('town', 128);
           $table->string('county', 128);
           $table->string('postcode', 16);
           
           $table->decimal('longitude', 15, 8);
           $table->decimal('latitude', 15, 8);

           $table->integer('user_id')->unsigned();
           $table->integer('type_id')->unsigned();
           $table->integer('furnished_id')->unsigned();
           $table->integer('parking_id')->unsigned();
           $table->integer('garden_id')->unsigned();

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
        Schema::drop('properties');
    }

}
