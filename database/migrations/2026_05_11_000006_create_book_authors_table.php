<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_authors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->string('contribution_type', 40)->default('author');
            $table->decimal('royalty_percentage', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['book_id', 'author_id']);
            $table->index(['author_id', 'contribution_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_authors');
    }
};

