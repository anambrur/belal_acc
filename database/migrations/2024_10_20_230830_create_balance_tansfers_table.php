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
        Schema::create('balance_tansfers', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->decimal('amount',10,2);
            $table->date('date');
            $table->string('description',150)->nullable();
            $table->string('status');
            $table->foreignId('from_accounts_id')->constrained('accounts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_accounts_id')->constrained('accounts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_tansfers');
    }
};
