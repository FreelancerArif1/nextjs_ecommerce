<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();
            $table->string('product_type',128);
            $table->string('category_id',32);
            $table->integer('attribute_set_id')->nullable();
            $table->string('title',256);
            $table->string('barcode',256)->nullable();
            $table->string('default_image',256)->nullable();
            $table->string('gallery_images',256)->nullable();
            $table->text('short_description');
            $table->longText('description');
            $table->string('slug')->unique();
            $table->decimal('price', 16,2);
            $table->decimal('product_cost', 16,2);
            $table->decimal('vat', 16,2)->nullable();
            $table->decimal('packaging_cost', 16,2)->nullable();
            $table->decimal('handling_fee', 16,2)->nullable();
            $table->decimal('loyalty_point', 16,2)->nullable();
            $table->decimal('list_price', 16,2)->nullable();
            $table->decimal('tour_price', 16,2)->nullable();
            $table->decimal('special_price', 16,2)->nullable();
            $table->string('special_price_type',128)->nullable();
            $table->dateTime('special_price_start')->nullable();
            $table->dateTime('special_price_end')->nullable();
            $table->string('sku',256)->nullable();
            $table->smallInteger('manage_stock');
            $table->integer('qty')->unsigned()->nullable();
            $table->integer('weight')->unsigned()->nullable();
            $table->string('weight_unit',256)->nullable();
            $table->smallInteger('in_stock')->nullable();
            $table->integer('viewed')->unsigned()->nullable();
            $table->tinyInteger('is_approximate')->nullable();
            $table->tinyInteger('is_active');
            $table->tinyInteger('is_deleted')->default(0);
            $table->integer('seller_id');
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
        Schema::dropIfExists('products');
    }
}
