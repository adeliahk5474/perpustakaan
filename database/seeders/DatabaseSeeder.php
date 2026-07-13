<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────
        $admin = User::create([
            'name'      => 'Admin PerpusKu',
            'email'     => 'admin@perpusku.edu',
            'member_id' => 'ADMIN-001',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
        ]);
    }
}
