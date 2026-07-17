<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('record_id')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->date('borrowed_date')->nullable();   // diisi saat admin konfirmasi
            $table->date('due_date')->nullable();        // diisi saat admin konfirmasi
            $table->date('returned_date')->nullable();
            $table->enum('status', [
                'pending',    // member ajukan, belum dikonfirmasi admin
                'borrowed',   // admin konfirmasi → buku aktif dipinjam
                'returned',   // admin konfirmasi pengembalian
                'overdue',    // melewati due_date
                'rejected',   // admin tolak pengajuan
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
