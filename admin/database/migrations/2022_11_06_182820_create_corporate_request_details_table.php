<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorporateRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_request_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->autoIncrement();
            $table->integer('user_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('seller_id')->nullable();
            $table->integer('corporate_request_id', 15)->nullable();
            $table->integer('qty')->nullable();
            $table->double('price', 16, 2)->nullable();
            $table->double('discount', 16, 2)->nullable();
            $table->text('variable_option')->nullable();
            $table->string('variable_sku')->nullable();
            $table->integer('status')->nullable();
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('corporate_request_details');
    }
}