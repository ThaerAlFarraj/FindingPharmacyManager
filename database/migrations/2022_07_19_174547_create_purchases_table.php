<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('deliver_id')->unsigned();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->foreign('deliver_id')->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->boolean('status')->default(false);
            $table->boolean('acceptable')->default(false);
            $table->float('total_price')->nullable();
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
        Schema::dropIfExists('purchases');
    }
};
