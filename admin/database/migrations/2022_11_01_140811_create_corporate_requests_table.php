<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorporateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('qty')->nullable();
            $table->double('amount', 16, 2)->nullable();
            $table->double('discount', 16, 2)->nullable();
            $table->double('payment_amount', 16, 2)->nullable();
            $table->string('invoice')->nullable();
            $table->string('work_order')->nullable();
            $table->dateTime('preferable_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('payment_details')->nullable();
            $table->integer('payment_status')->nullable();
            $table->integer('deal_status')->nullable();
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
        Schema::dropIfExists('corporate_requests');
    }
}