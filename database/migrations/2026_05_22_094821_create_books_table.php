<?php
// database/migrations/2026_05_22_094821_create_books_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('published_year')->nullable();
            $table->string('category')->nullable();   // bebas diisi manual, bukan enum
            $table->text('deskripsi')->nullable();    // ganti dari synopsis
            $table->integer('stok')->default(1);      // ganti dari total_copies
            $table->integer('available_copies')->default(1);
            $table->string('cover_image')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('rating_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
