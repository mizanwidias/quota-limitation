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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('cust_id')->unique()->nullable(false);
            $table->string('cust_name', 50)->nullable(false);
            $table->string('no_hp', 15)->unique()->nullable(false);
            $table->string('password');
            $table->enum('role', ['administrasi', 'customer', 'pemilik']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
