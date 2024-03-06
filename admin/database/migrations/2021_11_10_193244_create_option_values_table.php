<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('option_id')->index();
            $table->string('title',128);
            $table->string('sku',64)->nullable();
            $table->decimal('price',18,2);
            $table->string('price_type',32);
            $table->string('image',256);
            $table->tinyInteger('position')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('option_id')
            ->references('id')
            ->on('options')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_values');
    }
}
