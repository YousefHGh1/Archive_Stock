<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all existing suppliers
        Supplier::truncate();

        // Re-enable foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $suppliers = [
            ['num_supplier' => 1, 'name_supplier' => 'مصلحة المياه'],
            ['num_supplier' => 2, 'name_supplier' => 'الطوارئ'],
            ['num_supplier' => 3, 'name_supplier' => 'UNDP'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
