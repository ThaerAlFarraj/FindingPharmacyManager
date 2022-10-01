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
        Schema::create('donated_medicines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('pharmacy_id')->unsigned();
            $table->integer('medicine_id')->unsigned();
            $table->foreign('user_id')->references('user_id')
            ->on('cus_with_phar')
            ->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('pharmacy_id')
            ->on('cus_with_phar')
            ->onDelete('cascade');
            $table->foreign('medicine_id')->references('medicines_id')
            ->on('med__in__phares')
            ->onDelete('cascade');
            $table->enum('status', ['Taking', 'giving']);
            $table->integer('quantity');
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
        Schema::dropIfExists('donated_medicines');
    }
};
