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
        Schema::create('sells_returns', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->index();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('sell_price',10,2);
            $table->decimal('total_amount',10,2);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells_returns');
    }
};
