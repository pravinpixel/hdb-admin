<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_ref');
            $table->string('item_id');
            $table->string('item_name');
            $table->longText('cover_image')->nullable();
            $table->longText('item_description');
            $table->integer('no_of_page');
            $table->integer('loan_days');
            $table->unsignedBigInteger('category_id')->unsigned();
            $table->unsignedBigInteger('subcategory_id')->unsigned();
            $table->unsignedBigInteger('genre_id')->unsigned();
            $table->unsignedBigInteger('type_id')->unsigned();
            $table->boolean('is_need_approval')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('status')->default(0);
            $table->boolean('is_issued')->default(0);
            $table->integer('checkout_by')->unsigned()->nullable();
            $table->date('date_of_return')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->datetime('deleted_at')->nullable();
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
        Schema::dropIfExists('items');
    }
}
