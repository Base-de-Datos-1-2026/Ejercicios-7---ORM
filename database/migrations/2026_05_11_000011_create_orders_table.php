<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('status', 30)->default('pending');
            $table->dateTime('ordered_at');
            $table->dateTime('shipped_at')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('payment_method', 40);
            $table->timestamps();

            $table->index(['status', 'ordered_at']);
            $table->index(['customer_id', 'ordered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

