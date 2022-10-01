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
        Schema::create('cus_with_phar', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('pharmacy_id')->unsigned();
            $table->foreign('user_id')->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->foreign('pharmacy_id')->references('id')
            ->on('pharmacies')
            ->onDelete('cascade');
            $table->primary(['user_id', 'pharmacy_id']);
            $table->boolean('blacklist')->default(false);
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
        Schema::dropIfExists('cus_with_phar');
    }
};
