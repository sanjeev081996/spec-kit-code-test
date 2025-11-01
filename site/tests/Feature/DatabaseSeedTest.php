<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_is_seeded_with_core_entities(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('packages', ['slug' => 'golden-triangle-tour']);
        $this->assertDatabaseHas('destinations', ['slug' => 'goa']);
        $this->assertDatabaseHas('testimonials', ['author' => 'A. Sharma']);
    }
}

