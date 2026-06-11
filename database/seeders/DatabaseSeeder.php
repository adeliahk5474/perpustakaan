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
        // Admin user
        $admin = User::create([
            'name'      => 'Admin PerpusKu',
            'email'     => 'admin@perpusku.edu',
            'member_id' => 'ADMIN-001',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
        ]);

        // Sample members
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

        // Sample books
        $books = [
            [
                'title' => 'The Digital Archive: Principles of Preservation',
                'author' => 'Sarah Jenkins, PhD',
                'isbn' => '978-3-16-148410-0',
                'category' => 'Computer Science',
                'total_copies' => 3,
                'available_copies' => 2,
                'shelf_location' => 'Section A, Row 1',
                'shelf_section' => 'Computer Science Wing',
                'pages' => 320,
                'language' => 'English',
                'synopsis' => 'A comprehensive guide to digital preservation methods and archive principles in the modern age.',
                'rating' => 4.5,
                'rating_count' => 87,
            ],
            [
                'title' => 'Quantum Computation in Modern Systems',
                'author' => 'Dr. Robert Chen',
                'isbn' => 'TK7874.8',
                'category' => 'Computer Science',
                'total_copies' => 2,
                'available_copies' => 0,
                'shelf_location' => 'Section A, Row 2',
                'shelf_section' => 'Computer Science Wing',
                'pages' => 512,
                'language' => 'English',
                'synopsis' => 'An advanced exploration of quantum computing principles and their practical applications.',
                'rating' => 4.8,
                'rating_count' => 142,
            ],
            [
                'title' => 'Advanced Algorithmic Design Patterns',
                'author' => 'Marcus Aurelius Thorne',
                'isbn' => 'QA76.6',
                'category' => 'Computer Science',
                'total_copies' => 4,
                'available_copies' => 4,
                'shelf_location' => 'Section A, Row 3',
                'shelf_section' => 'Computer Science Wing',
                'pages' => 448,
                'language' => 'English',
                'synopsis' => 'Master algorithmic thinking and design patterns for scalable software development.',
                'rating' => 4.6,
                'rating_count' => 203,
            ],
            [
                'title' => 'Ethics in the Age of Artificial Intelligence',
                'author' => 'Elena Vassilyeva',
                'isbn' => 'BJ1031.E8',
                'category' => 'Philosophy',
                'total_copies' => 3,
                'available_copies' => 3,
                'shelf_location' => 'Section B, Row 5',
                'shelf_section' => 'Humanities Wing',
                'pages' => 276,
                'language' => 'English',
                'synopsis' => 'A philosophical inquiry into the moral implications of artificial intelligence on society.',
                'rating' => 4.3,
                'rating_count' => 95,
            ],
            [
                'title' => 'The Architecture of Light',
                'author' => 'Dr. Elena Vorich',
                'isbn' => '978-3-16-148410-1',
                'publisher' => 'Lumina Press',
                'published_year' => 2023,
                'category' => 'Architecture',
                'total_copies' => 3,
                'available_copies' => 3,
                'shelf_location' => 'Section A, Row 12',
                'shelf_section' => 'Fine Arts & Architecture Wing',
                'pages' => 412,
                'language' => 'English',
                'synopsis' => 'A definitive exploration of how natural and artificial light shapes our perception of built environments.',
                'rating' => 4.8,
                'rating_count' => 124,
            ],
            [
                'title' => 'Modern Architectural Principles',
                'author' => 'Jonathan K. Sterling',
                'isbn' => '978-3-16-148410-2',
                'category' => 'Architecture',
                'total_copies' => 2,
                'available_copies' => 1,
                'shelf_location' => 'Section A, Row 11',
                'shelf_section' => 'Fine Arts & Architecture Wing',
                'pages' => 388,
                'language' => 'English',
                'synopsis' => 'Explore the principles that define modern architectural design and urban planning.',
                'rating' => 4.2,
                'rating_count' => 78,
            ],
            [
                'title' => 'The Psychology of Money',
                'author' => 'Morgan Housel',
                'isbn' => '978-0-857-19763-4',
                'category' => 'Non-Fiction',
                'total_copies' => 5,
                'available_copies' => 5,
                'shelf_location' => 'Section C, Row 1',
                'shelf_section' => 'Business & Finance Wing',
                'pages' => 256,
                'language' => 'English',
                'synopsis' => 'Timeless lessons on wealth, greed, and happiness through fascinating stories and behavioral insights.',
                'rating' => 4.9,
                'rating_count' => 512,
            ],
            [
                'title' => 'Design for the Real World',
                'author' => 'Victor Papanek',
                'isbn' => '978-0-897-33153-4',
                'category' => 'Non-Fiction',
                'total_copies' => 2,
                'available_copies' => 0,
                'shelf_location' => 'Section D, Row 3',
                'shelf_section' => 'Design Wing',
                'pages' => 394,
                'language' => 'English',
                'synopsis' => 'A manifesto for responsible design that serves humanity and the environment.',
                'rating' => 4.4,
                'rating_count' => 167,
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0-743-27356-5',
                'category' => 'Literature',
                'total_copies' => 6,
                'available_copies' => 5,
                'shelf_location' => 'Section E, Row 2',
                'shelf_section' => 'Literature Wing',
                'pages' => 180,
                'language' => 'English',
                'synopsis' => 'A story of the mysteriously wealthy Jay Gatsby and his obsessive love for Daisy Buchanan.',
                'rating' => 4.1,
                'rating_count' => 890,
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'isbn' => '978-0-441-01359-7',
                'category' => 'Science Fiction',
                'total_copies' => 4,
                'available_copies' => 3,
                'shelf_location' => 'Section F, Row 1',
                'shelf_section' => 'Science Fiction Wing',
                'pages' => 896,
                'language' => 'English',
                'synopsis' => 'Set in a distant future, Dune tells the story of young Paul Atreides and his journey on a desert planet.',
                'rating' => 4.7,
                'rating_count' => 1203,
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }

        // Sample loans
        $book1 = Book::where('title', 'Modern Architectural Principles')->first();
        $book2 = Book::where('title', 'Quantum Computation in Modern Systems')->first();

        Loan::create([
            'record_id'    => 'TX-8842',
            'user_id'      => $member2->id,
            'book_id'      => $book1->id,
            'borrowed_date' => Carbon::now()->subDays(20),
            'due_date'     => Carbon::now()->subDays(6),
            'status'       => 'overdue',
        ]);

        Loan::create([
            'record_id'    => 'TX-8710',
            'user_id'      => $member1->id,
            'book_id'      => $book2->id,
            'borrowed_date' => Carbon::now()->subDays(5),
            'due_date'     => Carbon::now()->addDays(9),
            'status'       => 'borrowed',
        ]);
    }
}
