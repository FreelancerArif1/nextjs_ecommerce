<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->unsignedInteger('sub_total');
            $table->unsignedInteger('total');
            $table->string('bank_tran_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_issuer')->nullable();
            $table->string('card_issuer_country')->nullable();
            $table->string('card_no')->nullable();
            $table->string('card_type')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
