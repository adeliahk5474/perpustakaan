<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'user_id',
        'book_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date'      => 'date',
        'returned_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function getDaysLateAttribute(): int
    {
        if (!$this->due_date || $this->status === 'returned') return 0;
        $today = Carbon::today();
        return $today->gt($this->due_date) ? $today->diffInDays($this->due_date) : 0;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'overdue' ||
            ($this->status === 'borrowed' && $this->due_date && Carbon::today()->gt($this->due_date));
    }

    public static function generateRecordId(): string
    {
        $last = static::orderBy('id', 'desc')->first();
        $nextNum = $last ? (int) substr($last->record_id, 3) + 1 : 1001;
        return 'TX-' . $nextNum;
    }

    public static function updateOverdueStatuses(): void
    {
        static::where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu Konfirmasi',
            'borrowed' => 'Sedang Dipinjam',
            'returned' => 'Dikembalikan',
            'overdue'  => 'Terlambat',
            'rejected' => 'Ditolak',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'bg-yellow-100 text-yellow-700',
            'borrowed' => 'bg-blue-100 text-blue-700',
            'returned' => 'bg-green-100 text-green-700',
            'overdue'  => 'bg-red-100 text-red-700',
            'rejected' => 'bg-gray-100 text-gray-600',
            default    => 'bg-gray-100 text-gray-600',
        };
    }
}
