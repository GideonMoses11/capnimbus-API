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
        Schema::create('kycs', function (Blueprint $table) {
            $table->string('id')->primary()->index();
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('document')->nullable();
            $table->string('wallet_address')->nullable();
            $table->enum('status', ['pending', 'rejected', 'approved'])->default('pending');
            $table->string('user_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
