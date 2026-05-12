<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('is_vip')->default(false);
            $table->timestamps();

            $table->index(['is_vip', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

