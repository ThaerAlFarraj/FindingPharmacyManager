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
        Schema::create('med_purs', function (Blueprint $table) {
            $table->integer('purchase_id')->unsigned();
            $table->integer('medicine_id')->unsigned();
            $table->integer('pharmacy_id')->unsigned();
            $table->foreign('purchase_id')->references('id')
            ->on('purchases')
            ->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('pharmacies_id')
            ->on('med__in__phares')
            ->onDelete('cascade');
            $table->foreign('medicine_id')->references('medicines_id')
            ->on('med__in__phares')
            ->onDelete('cascade');
            $table->primary(['purchase_id', 'medicine_id','pharmacy_id']);
            $table->integer('quantity');
            $table->float('price');
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
        Schema::dropIfExists('med_purs');
    }
};
