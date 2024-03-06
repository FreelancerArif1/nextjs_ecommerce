<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
			$table->string('product_sku')->nullable();
            $table->integer('product_qty')->unsigned();
            $table->text('product_options')->nullable();
			$table->string('shipping_method')->nullable();
			$table->integer('shipping_cost')->nullable();
            $table->decimal('price', 16,2);
            $table->integer('seller_id')->unsigned();
            $table->int('status')->default(1);
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
        Schema::dropIfExists('order_details');
    }
}
