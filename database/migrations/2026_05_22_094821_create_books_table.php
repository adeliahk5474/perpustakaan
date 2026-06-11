<?php
// database/migrations/2024_01_01_000002_create_books_table.php

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
            $table->string('isbn')->unique()->nullable();
            $table->string('publisher')->nullable();
            $table->year('published_year')->nullable();
            $table->enum('category', [
                'Science Fiction',
                'Computer Science',
                'Philosophy',
                'Literature',
                'History',
                'Architecture',
                'Non-Fiction',
                'Other'
            ])->default('Other');
            $table->text('synopsis')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->string('shelf_location')->nullable();   // e.g. "Section A, Row 12"
            $table->string('shelf_section')->nullable();    // e.g. "Fine Arts & Architecture Wing"
            $table->integer('pages')->nullable();
            $table->string('language')->default('Indonesian');
            $table->string('cover_image')->nullable();
            $table->boolean('is_ebook')->default(false);
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
