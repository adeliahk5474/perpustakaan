<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan sistem.
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Simpan pengaturan (profil perpustakaan, aturan peminjaman, denda, notifikasi).
     * Semua section memakai endpoint yang sama; field "section" hanya penanda
     * agar mudah dibedakan saat debugging / logging, tidak wajib dipakai dalam logika.
     */
    public function update(Request $request)
    {
        $request->validate([
            'section' => 'nullable|string',

            // Profil Perpustakaan
            'library_name'   => 'sometimes|string|max:255',
            'contact_email'  => 'sometimes|email|max:255',
            'contact_phone'  => 'sometimes|nullable|string|max:30',
            'address'        => 'sometimes|nullable|string|max:500',
            'open_time'      => 'sometimes|nullable|string',
            'close_time'     => 'sometimes|nullable|string',

            // Aturan Peminjaman
            'max_loans_per_member'   => 'sometimes|integer|min:1|max:50',
            'loan_duration_days'     => 'sometimes|integer|min:1|max:365',
            'max_renewals'           => 'sometimes|integer|min:0|max:10',
            'renewal_duration_days'  => 'sometimes|integer|min:1|max:90',
            'block_on_overdue'       => 'sometimes|boolean',

            // Denda
            'fine_per_day'                => 'sometimes|integer|min:0',
            'max_fine_per_book'            => 'sometimes|integer|min:0',
            'suspension_threshold_days'    => 'sometimes|integer|min:0',

            // Notifikasi
            'notify_due_soon'              => 'sometimes|boolean',
            'notify_overdue'               => 'sometimes|boolean',
            'notify_wishlist_available'    => 'sometimes|boolean',
        ]);

        $data = $request->except(['_token', '_method', 'section']);

        // Untuk checkbox yang tidak dicentang, set eksplisit ke '0'
        // supaya tersimpan (checkbox yang tidak dicentang tidak terkirim oleh browser)
        $checkboxFields = [
            'block_on_overdue',
            'notify_due_soon',
            'notify_overdue',
            'notify_wishlist_available',
        ];

        $section = $request->input('section');

        $sectionCheckboxMap = [
            'aturan_peminjaman' => ['block_on_overdue'],
            'notifikasi'        => ['notify_due_soon', 'notify_overdue', 'notify_wishlist_available'],
        ];

        if (isset($sectionCheckboxMap[$section])) {
            foreach ($sectionCheckboxMap[$section] as $field) {
                if (!array_key_exists($field, $data)) {
                    $data[$field] = '0';
                }
            }
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Perbarui data akun admin (nama, email, kata sandi).
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Akun berhasil diperbarui.');
    }
}