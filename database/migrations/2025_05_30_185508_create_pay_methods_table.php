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
        Schema::create('pay_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('coin');
            $table->string('abr');
            $table->integer('factor');
            $table->integer('status');
            $table->string('user_name')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('user_dni')->nullable();
            $table->string('user_account')->nullable();
            $table->string('user_bank')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_methods');
    }
};
