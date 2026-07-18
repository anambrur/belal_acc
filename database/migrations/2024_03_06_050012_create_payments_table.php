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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('accounts_id')->constrained('accounts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('amount',10,2);
            $table->date('date');
            $table->integer('payable_id');
            $table->string('payable_type', 50);
            $table->string('payment_for',50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
