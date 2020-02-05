<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateExpenseAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('expense_id')->unsigned()->index();
            $table->foreign('expense_id')->references('id')->on('expenses');

            $table->binary('file');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `expense_attachments` MODIFY `file` MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_attachments');
    }
}
