<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
            $table->date('date');
            $table->decimal('balance');
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
        Schema::dropIfExists('daily_balances');
    }
}
