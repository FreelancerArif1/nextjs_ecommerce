<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorporateRequestNegotiationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_request_negotiations', function (Blueprint $table) {
            $table->id();
            $table->integer('corporate_request_id');
            $table->integer('user_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->longText('note')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('corporate_request_negotiations');
    }
}