<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->string('name');
            $table->string('slug');
            $table->string('code_2')->nullable(); // NA
            $table->string('code_3')->nullable(); // NAM
            $table->smallInteger('zoom_level')->default(10);
            $table->string('latitude', '50')->nullable();
            $table->string('longitude', '50')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('continent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}