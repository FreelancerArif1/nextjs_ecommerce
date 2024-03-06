<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',256);
            $table->string('attribute_code',256);
            $table->string('placeholder_text',256)->nullable();
            $table->text('attribute_values')->nullable();
            $table->text('description')->nullable();
            $table->text('catalog_input_type')->nullable();
            $table->tinyInteger('is_required')->default(0);
            $table->tinyInteger('show_on_specification')->default(1);
            $table->tinyInteger('show_on_advance_search')->default(1);
            $table->tinyInteger('show_on_filter')->default(1);
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('attributes');
    }
}
