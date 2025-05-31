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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->integer('quatity');
            $table->string('reference')->nullable();
            $table->longText('vaucher');
            $table->date('pay_date');
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unsignedBigInteger('rifa_id');
            $table->foreign('rifa_id')->references('id')->on('rifas')->onDelete('cascade');
            $table->unsignedBigInteger('method_id');
            $table->foreign('method_id')->references('id')->on('pay_methods')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden');
    }
};
