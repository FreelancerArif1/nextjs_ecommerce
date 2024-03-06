<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('product_id');
            $table->string('product_type',64)->nullable();
            $table->string('row_id',256)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
			$table->integer('user_division_id')->nullable();
			$table->integer('user_district_id')->nullable();
            $table->unsignedBigInteger('seller_id')->index();
			$table->integer('seller_division_id')->nullable();
			$table->integer('seller_district_id')->nullable();
			$table->double('price', 16, 2);
            $table->unsignedInteger('qty');
			$table->double('discount', 16, 2);
			$table->string('shipping_method',256)->nullable();
			$table->double('shipping_cost',16,2)->nullable();
			$table->text('variable_options')->nullable();
			
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
        Schema::dropIfExists('carts');
    }
}
