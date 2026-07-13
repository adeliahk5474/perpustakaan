<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan perpustakaan.
     */
    public function index(Request $request)
    {
        $period = (int) $request->get('period', 30);
        $from   = Carbon::now()->subDays($period);

        $loansInPeriod = Loan::where('created_at', '>=', $from);

        $totalLoans    = (clone $loansInPeriod)->count();
        $totalReturned = (clone $loansInPeriod)->where('status', 'returned')->count();
        $totalOverdue  = (clone $loansInPeriod)->where('status', 'overdue')->count();
        $returnRate    = $totalLoans > 0 ? round(($totalReturned / $totalLoans) * 100) : 0;

        // Jika tabel loans Anda punya kolom denda (mis. fine_amount), ini akan dijumlahkan.
        // Jika belum ada kolomnya, baris ini aman dan akan menghasilkan 0.
        $totalFines = $this->hasColumn('loans', 'fine_amount')
            ? (clone $loansInPeriod)->sum('fine_amount')
            : 0;

        // Tren peminjaman vs pengembalian per hari (maks 14 titik agar grafik tetap rapi)
        $days = min($period, 14);
        $trends = collect(range(0, $days - 1))->map(function ($i) use ($days) {
            $date = Carbon::now()->subDays($days - 1 - $i)->startOfDay();
            return [
                'label'    => $date->format('d/m'),
                'borrowed' => Loan::whereDate('borrowed_date', $date)->count(),
                'returned' => Loan::whereDate('returned_date', $date)->count(),
            ];
        });

        // Kategori buku terpopuler berdasarkan jumlah peminjaman
        $topCategories = Book::select('category', DB::raw('count(loans.id) as count'))
            ->join('loans', 'loans.book_id', '=', 'books.id')
            ->where('loans.created_at', '>=', $from)
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $maxCatCount = $topCategories->max('count') ?: 1;
        $topCategories = $topCategories->map(function ($c) use ($maxCatCount) {
            return [
                'category'   => $c->category,
                'count'      => $c->count,
                'percentage' => round(($c->count / $maxCatCount) * 100),
            ];
        });

        // Buku paling sering dipinjam
        $mostBorrowedBooks = Book::withCount(['loans' => function ($q) use ($from) {
                $q->where('created_at', '>=', $from);
            }])
            ->orderByDesc('loans_count')
            ->limit(5)
            ->get();

        // Anggota paling aktif meminjam
        $mostActiveMembers = User::where('role', 'member')
            ->withCount(['loans' => function ($q) use ($from) {
                $q->where('created_at', '>=', $from);
            }])
            ->orderByDesc('loans_count')
            ->limit(5)
            ->get();

        // Tabel detail transaksi (dengan paginasi)
        $transactions = Loan::with(['user', 'book'])
            ->where('created_at', '>=', $from)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'totalLoans',
            'totalReturned',
            'totalOverdue',
            'returnRate',
            'totalFines',
            'trends',
            'topCategories',
            'mostBorrowedBooks',
            'mostActiveMembers',
            'transactions'
        ));
    }

    /**
     * Ekspor laporan transaksi peminjaman ke file CSV.
     */
    public function export(Request $request)
    {
        $period = (int) $request->get('period', 30);
        $from   = Carbon::now()->subDays($period);

        $transactions = Loan::with(['user', 'book'])
            ->where('created_at', '>=', $from)
            ->latest()
            ->get();

        $filename = 'laporan-peminjaman-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            // Tambahkan BOM agar Excel membaca karakter UTF-8 (seperti "é", "ü") dengan benar
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ID', 'Anggota', 'Email', 'Buku', 'Tgl Pinjam', 'Tenggat', 'Tgl Kembali', 'Status']);

            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $trx->record_id ?? $trx->id,
                    optional($trx->user)->name,
                    optional($trx->user)->email,
                    optional($trx->book)->title,
                    optional($trx->borrowed_date)->format('Y-m-d'),
                    optional($trx->due_date)->format('Y-m-d'),
                    optional($trx->returned_date)->format('Y-m-d'),
                    $trx->status,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Helper kecil untuk cek keberadaan kolom secara aman,
     * supaya controller tidak error jika kolom fine_amount belum ada.
     */
    private function hasColumn(string $table, string $column): bool
    {
        return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
    }
}