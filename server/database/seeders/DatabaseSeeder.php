<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Start a database transaction before seeding data.
     */
    public function __construct()
    {
        DB::beginTransaction();
    }

    /**
     * Commit the database transaction after seeding data.
     */
    public function __destruct()
    {
        DB::commit();
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CalendarActionTemplateSeeder::class,
            ClientEmployeeSeeder::class,
        ]);
    }
}
