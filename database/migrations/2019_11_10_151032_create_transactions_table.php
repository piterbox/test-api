<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 32);
            $table->date('date');
            $table->decimal('amount');
            $table->string('text', 128);
            $table->decimal('balance');
            $table->json('tags');
            $table->integer('account_id');
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('customer_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
