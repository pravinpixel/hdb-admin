<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->unsigned();
            $table->unsignedBigInteger('checkout_id')->unsigned();
            $table->integer('returned_by')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('checkout_id')->references('id')->on('checkouts');
            $table->foreign('returned_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('return_items');
    }
}
