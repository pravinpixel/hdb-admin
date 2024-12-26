<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApproveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->unsigned();
            $table->integer('requested_by')->unsigned();
            $table->integer('approved_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->boolean('approve_status')->default(true);
            $table->date('date_of_return')->nullable();
            $table->boolean('email_status')->default(0);
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('requested_by')->references('id')->on('users');
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
        Schema::dropIfExists('approve_requests');
    }
}
