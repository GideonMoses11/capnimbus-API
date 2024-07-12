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
        Schema::create('investments', function (Blueprint $table) {
            $table->string('id')->primary()->index();
            $table->float('amount_usd');
            $table->float('roi')->nullable();
            $table->dateTime('expiry_date');
            $table->enum('status', ['in-progress', 'completed', 'paid', 'terminated'])->default('in-progress');
            $table->string('user_id')->index();
            $table->string('plan_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
