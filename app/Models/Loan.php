<?php
// app/Models/Loan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'is_renewed',
        'renewal_count',
        'notes',
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'is_renewed' => 'boolean',
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
        if ($this->status === 'returned') return 0;
        $today = Carbon::today();
        if ($today->gt($this->due_date)) {
            return $today->diffInDays($this->due_date);
        }
        return 0;
    }

    public function getDaysUntilDueAttribute(): int
    {
        if ($this->status === 'returned') return 0;
        $today = Carbon::today();
        if ($today->lte($this->due_date)) {
            return $today->diffInDays($this->due_date);
        }
        return 0;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'overdue' ||
            ($this->status === 'borrowed' && Carbon::today()->gt($this->due_date));
    }

    public static function generateRecordId(): string
    {
        $last = static::orderBy('id', 'desc')->first();
        $nextNum = $last ? (int) substr($last->record_id, 3) + 1 : 1001;
        return 'TX-' . $nextNum;
    }

    // Auto-update overdue status
    public static function updateOverdueStatuses(): void
    {
        static::where('status', 'borrowed')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);
    }
}
