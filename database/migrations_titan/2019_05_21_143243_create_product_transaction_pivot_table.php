<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransactionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transaction', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->integer('quantity');
            $table->integer('transaction_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();

            //$table->integer('product_id')->unsigned()->index();
            //$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            //$table->integer('transaction_id')->unsigned()->index();
            //$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            //$table->primary(['product_id', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_transaction');
    }
}