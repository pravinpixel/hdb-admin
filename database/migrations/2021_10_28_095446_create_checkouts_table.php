<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('item_id')->unsigned();
            $table->integer('checkout_by')->unsigned();
            $table->integer('return_id')->unsigned()->nullable();
            $table->date('date_of_return');
            $table->boolean('return_status')->default(0);
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('checkout_by')->references('id')->on('users');
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
        Schema::dropIfExists('checkouts');
    }
}
