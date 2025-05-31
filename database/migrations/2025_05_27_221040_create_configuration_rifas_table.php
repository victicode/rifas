<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configuration_rifas', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity_tickets');
            $table->integer('price');
            $table->integer('minumus_buy');
            $table->integer('auto_select');
            $table->longText('banner_img');
            $table->longText('all_image');
            $table->unsignedBigInteger('rifa_id');
            $table->foreign('rifa_id')->references('id')->on('rifas')->onDelete('cascade');
            $table->unsignedBigInteger('create_by');
            $table->foreign('create_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_rifas');
    }
};
