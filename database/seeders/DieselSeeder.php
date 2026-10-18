<?php

namespace Database\Seeders;

use App\Models\Diesel;
use App\Models\Supplier;
use App\Models\TypesFuel;
use Illuminate\Database\Seeder;

class DieselSeeder extends Seeder
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

        // Delete all existing diesel records
        Diesel::truncate();

        // Re-enable foreign key constraints
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get supplier IDs
        $supplierMap = [
            'طوارئ' => Supplier::where('name_supplier', 'الطوارئ')->first()->id,
            'بلدية' => Supplier::where('name_supplier', 'مصلحة المياه')->first()->id, // Assuming بلدية maps to مصلحة المياه
            'مصلحة' => Supplier::where('name_supplier', 'مصلحة المياه')->first()->id,
            'UNDP' => Supplier::where('name_supplier', 'UNDP')->first()->id,
        ];

        // Get or create TypesFuel for "سولار"
        $typesFuel = TypesFuel::firstOrCreate(['name' => 'سولار']);

        $dieselData = [
            ['supplier' => 'طوارئ', 'quantity' => 1765, 'invoice_num' => 0, 'voucher' => 3, 'date' => '2026-03-01', 'type' => 'طوارئ'],
            ['supplier' => 'بلدية', 'quantity' => 1088, 'invoice_num' => 0, 'voucher' => 4, 'date' => '2026-03-01', 'type' => 'بلدية'],
            ['supplier' => 'مصلحة', 'quantity' => 1500, 'invoice_num' => 0, 'voucher' => 5, 'date' => '2026-03-04', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 3100, 'invoice_num' => 0, 'voucher' => 6, 'date' => '2026-03-10', 'type' => 'مصلحة'],
            ['supplier' => 'طوارئ', 'quantity' => 5400, 'invoice_num' => 0, 'voucher' => 7, 'date' => '2026-03-10', 'type' => 'طوارئ'],
            ['supplier' => 'مصلحة', 'quantity' => 2300, 'invoice_num' => 0, 'voucher' => 8, 'date' => '2026-03-26', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 2300, 'invoice_num' => 0, 'voucher' => 9, 'date' => '2026-03-26', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 2300, 'invoice_num' => 0, 'voucher' => 10, 'date' => '2026-04-02', 'type' => 'مصلحة'],
            ['supplier' => 'UNDP', 'quantity' => 300, 'invoice_num' => 0, 'voucher' => 11, 'date' => '2026-04-06', 'type' => 'UNDP'],
            ['supplier' => 'مصلحة', 'quantity' => 2300, 'invoice_num' => 0, 'voucher' => 12, 'date' => '2026-04-09', 'type' => 'مصلحة'],
            ['supplier' => 'UNDP', 'quantity' => 453, 'invoice_num' => 0, 'voucher' => 13, 'date' => '2026-04-12', 'type' => 'UNDP'],
            ['supplier' => 'مصلحة', 'quantity' => 2300, 'invoice_num' => 0, 'voucher' => 14, 'date' => '2026-04-13', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 2700, 'invoice_num' => 0, 'voucher' => 15, 'date' => '2026-04-23', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 2800, 'invoice_num' => 0, 'voucher' => 16, 'date' => '2026-05-05', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 2800, 'invoice_num' => 0, 'voucher' => 17, 'date' => '2026-05-10', 'type' => 'مصلحة'],
            ['supplier' => 'UNDP', 'quantity' => 18, 'invoice_num' => 0, 'voucher' => 18, 'date' => '2026-05-13', 'type' => 'UNDP'],
            ['supplier' => 'مصلحة', 'quantity' => 3800, 'invoice_num' => 0, 'voucher' => 19, 'date' => '2026-05-14', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 3000, 'invoice_num' => 0, 'voucher' => 20, 'date' => '2026-05-20', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 3000, 'invoice_num' => 0, 'voucher' => 21, 'date' => '2026-05-26', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 400, 'invoice_num' => 0, 'voucher' => 22, 'date' => '2026-06-03', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 2300, 'invoice_num' => 0, 'voucher' => 23, 'date' => '2026-06-08', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 750, 'invoice_num' => 0, 'voucher' => 24, 'date' => '2026-06-09', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 3200, 'invoice_num' => 0, 'voucher' => 25, 'date' => '2026-06-16', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 3550, 'invoice_num' => 0, 'voucher' => 26, 'date' => '2026-06-21', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 4100, 'invoice_num' => 0, 'voucher' => 27, 'date' => '2026-06-28', 'type' => 'مصلحة'],
            ['supplier' => 'مصلحة', 'quantity' => 3400, 'invoice_num' => 0, 'voucher' => 28, 'date' => '2026-07-06', 'type' => 'مصلحة'],
        ];

        foreach ($dieselData as $data) {
            Diesel::create([
                'supplier_id' => $supplierMap[$data['supplier']],
                'typesfuel_id' => $typesFuel->id,
                'voucher' => $data['voucher'],
                'type' => $data['type'],
                'invoice_num' => $data['invoice_num'],
                'quantity' => $data['quantity'],
                'date' => $data['date'],
            ]);
        }
    }
}
