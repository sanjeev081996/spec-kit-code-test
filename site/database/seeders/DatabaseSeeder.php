<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('packages')->updateOrInsert(
            ['slug' => 'golden-triangle-tour'],
            [
                'title' => 'Golden Triangle Tour',
                'summary' => 'Delhi, Agra, Jaipur highlights',
                'description' => 'Experience the iconic cities with curated experiences.',
                'price_from' => 19999,
                'duration' => '5D/4N',
                'is_published' => true,
                'updated_at' => now(), 'created_at' => now(),
            ]
        );

        DB::table('destinations')->updateOrInsert(
            ['slug' => 'goa'],
            [
                'name' => 'Goa',
                'description' => 'Beaches and nightlife',
                'hero_image' => null,
                'is_published' => true,
                'updated_at' => now(), 'created_at' => now(),
            ]
        );

        DB::table('testimonials')->updateOrInsert(
            ['author' => 'A. Sharma', 'quote' => 'Fantastic experience and great service!'],
            [
                'rating' => 5,
                'is_published' => true,
                'updated_at' => now(), 'created_at' => now(),
            ]
        );
    }
}
