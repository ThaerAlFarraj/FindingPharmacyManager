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
        Schema::create('med_lists', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('medicine_id')->unsigned();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')
            ->on('medicines')
            ->onDelete('cascade');
            $table->primary(['user_id', 'medicine_id']);
            $table->string('dosing_Times');
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
        Schema::dropIfExists('med_lists');
    }
};
