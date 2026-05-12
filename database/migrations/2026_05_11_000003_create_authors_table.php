<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('country', 80);
            $table->date('birth_date')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->index(['country', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};

