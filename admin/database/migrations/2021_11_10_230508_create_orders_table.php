<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedInteger('address_id')->index();
            $table->unsignedInteger('payment_id')->index();
            $table->string('ip_address')->nullable();
            $table->string('email')->nullable();
            $table->text('note')->nullable();
            $table->decimal('paid_amount', 16,2);
            $table->decimal('discount_amount', 16,2)->nullable();
            $table->string('coupon_code')->nullable();
			$table->integer('coupon_amount')->nullable();
            $table->string('payment_method')->nullable();
			$table->integer('shipping_cost')->nullable();
            $table->unsignedInteger('status')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

            $table->foreign('address_id')
				->references('id')
				->on('addresses')
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
        Schema::dropIfExists('orders');
    }
}
