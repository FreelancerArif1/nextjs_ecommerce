<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->int('type');
            $table->string('banner');
            $table->integer('voucher_category_id');
            $table->decimal('minimum_amount', 16,2)->nullable();
            $table->decimal('amount', 16,2)->nullable();
            $table->integer('quantity')->nullable();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->integer('max_qty_per_user')->nullable();
            $table->text('product_ids')->nullable();
            $table->text('category_ids')->nullable();
            $table->text('brand_ids')->nullable();
            $table->text('seller_ids')->nullable();
            $table->text('user_ids')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
}
