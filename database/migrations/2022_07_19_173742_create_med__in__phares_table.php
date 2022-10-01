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
        Schema::create('med__in__phares', function (Blueprint $table) {
            $table->integer('medicines_id')->unsigned();
            $table->integer('pharmacies_id')->unsigned();
            $table->foreign('medicines_id')->references('id')
            ->on('medicines')
            ->onDelete('cascade');
            $table->foreign('pharmacies_id')->references('id')
            ->on('pharmacies')
            ->onDelete('cascade');
            $table->primary(['medicines_id', 'pharmacies_id']);
            $table->integer('quan_In_Phar')->nullable();;
            $table->integer('quan_In_Don')->nullable();;
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
        Schema::dropIfExists('med__in__phares');
    }
};
