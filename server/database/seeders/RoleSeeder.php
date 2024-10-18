<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'CEO'
        ]);

        Role::firstOrCreate([
            'name' => 'Controller'
        ]);

        Role::firstOrCreate([
            'name' => 'Cash management specialist'
        ]);

        Role::firstOrCreate([
            'name' => 'Accountant'
        ]);
    }
}
