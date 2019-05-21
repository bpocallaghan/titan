<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckoutProductPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_product', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->integer('quantity');
            $table->integer('checkout_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();

            //$table->integer('checkout_id')->unsigned()->index();
            //$table->foreign('checkout_id')->references('id')->on('checkouts')->onDelete('cascade');
            //$table->integer('product_id')->unsigned()->index();
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            //$table->primary(['checkout_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('checkout_product');
    }
}