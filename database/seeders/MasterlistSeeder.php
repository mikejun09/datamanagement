<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\MasterList;

class MasterlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert a single record manually if desired
        DB::table('tbl_masterlist')->insert([
            'precinct' => Str::random(10),
            'name' => Str::random(10),
            'address' => Str::random(10),
        ]);

        // Use factory to create 50 records
        MasterList::factory()->count(50)->create();
    }
}
