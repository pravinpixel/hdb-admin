<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->date('issue_date');
            $table->unsignedBigInteger('item_id')->unsigned();
            $table->unsignedBigInteger('approve_request_id')->unsigned();
            $table->integer('approved_by')->unsigned();
            $table->integer('received_by')->unsigned();
            $table->boolean('issue_status')->default(false);
            $table->date('date_of_return');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('approve_request_id')->references('id')->on('approve_requests')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('issues');
    }
}
