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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->index();
            $table->foreignId('supplier_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('date')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->decimal('total_discount',10,2);
            $table->decimal('paid_amount', 10, 2);
            $table->integer('location_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
