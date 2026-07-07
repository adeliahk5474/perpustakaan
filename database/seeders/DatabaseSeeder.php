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

        // ── Members ───────────────────────────────────────────
        $member1 = User::create([
            'name'      => 'M. Aryan',
            'email'     => 'aryan@student.edu',
            'member_id' => 'STU-2024001',
            'password'  => Hash::make('member1'),
            'role'      => 'member',
        ]);

        $member2 = User::create([
            'name'      => 'Jane Doe',
            'email'     => 'jane@student.edu',
            'member_id' => 'STU-2024002',
            'password'  => Hash::make('member2'),
            'role'      => 'member',
        ]);
    }
}
