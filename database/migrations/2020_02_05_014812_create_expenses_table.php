<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('expense_claim_id')->unsigned()->index();
            $table->foreign('expense_claim_id')->references('id')->on('expense_claims');

            $table->bigInteger('expense_type_id')->unsigned()->index();
            $table->foreign('expense_type_id')->references('id')->on('expense_types');

            $table->bigInteger('currency_id')->unsigned()->index();
            $table->foreign('currency_id')->references('id')->on('currencies');
            
            $table->decimal('amount', 15, 0);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
