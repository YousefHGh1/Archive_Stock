<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 2, 'name' => 'admin', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'archive', 'guard_name' => 'web'],
            ['id' => 5, 'name' => 'stock', 'guard_name' => 'web'],
            ['id' => 6, 'name' => 'Super-User', 'guard_name' => 'web'],
            ['id' => 7, 'name' => 'legal', 'guard_name' => 'web'],
            ['id' => 8, 'name' => 'computer', 'guard_name' => 'web'],
            ['id' => 9, 'name' => 'jibaya', 'guard_name' => 'web'],
            ['id' => 10, 'name' => 'censorship', 'guard_name' => 'web'],
            ['id' => 12, 'name' => 'users', 'guard_name' => 'web'],
            // Module-specific manager roles
            ['id' => 13, 'name' => 'items-manager', 'guard_name' => 'web'],
            ['id' => 14, 'name' => 'categories-manager', 'guard_name' => 'web'],
            ['id' => 15, 'name' => 'units-manager', 'guard_name' => 'web'],
            ['id' => 16, 'name' => 'suppliers-manager', 'guard_name' => 'web'],
            ['id' => 17, 'name' => 'invoices-manager', 'guard_name' => 'web'],
            ['id' => 18, 'name' => 'invoice_exports-manager', 'guard_name' => 'web'],
            ['id' => 19, 'name' => 'custody-manager', 'guard_name' => 'web'],
            ['id' => 20, 'name' => 'currency-manager', 'guard_name' => 'web'],
            ['id' => 21, 'name' => 'employee-manager', 'guard_name' => 'web'],
            ['id' => 22, 'name' => 'diesels-manager', 'guard_name' => 'web'],
            ['id' => 23, 'name' => 'dieselexport-manager', 'guard_name' => 'web'],
            ['id' => 24, 'name' => 'report-manager', 'guard_name' => 'web'],
            ['id' => 25, 'name' => 'sections-manager', 'guard_name' => 'web'],
            ['id' => 26, 'name' => 'stores-manager', 'guard_name' => 'web'],
            ['id' => 27, 'name' => 'purchases-manager', 'guard_name' => 'web'],
            ['id' => 28, 'name' => 'archive-manager', 'guard_name' => 'web'],
            ['id' => 29, 'name' => 'legal-manager', 'guard_name' => 'web'],
            ['id' => 30, 'name' => 'computer-manager', 'guard_name' => 'web'],
            ['id' => 31, 'name' => 'censorship-manager', 'guard_name' => 'web'],
            ['id' => 32, 'name' => 'jibaya-manager', 'guard_name' => 'web'],
            // Department roles for purchase request notifications
            ['id' => 33, 'name' => 'purchase', 'guard_name' => 'web'],
            ['id' => 34, 'name' => 'finance', 'guard_name' => 'web'],
            ['id' => 35, 'name' => 'tender_committee', 'guard_name' => 'web'],
            // ======================== Stock & Fuel Specialized Roles ========================
            ['id' => 36, 'name' => 'stock-fuel-reader', 'guard_name' => 'web'],
            ['id' => 37, 'name' => 'stock-fuel-creator', 'guard_name' => 'web'],
            ['id' => 38, 'name' => 'stock-fuel-updater', 'guard_name' => 'web'],
            ['id' => 39, 'name' => 'stock-fuel-deleter', 'guard_name' => 'web'],
            // ======================== Full Fuel Access Role ========================
            ['id' => 40, 'name' => 'fuel-full-access', 'guard_name' => 'web'],
            // ======================== Full Access Roles ========================
            ['id' => 41, 'name' => 'stock-full-access', 'guard_name' => 'web'],
            ['id' => 42, 'name' => 'purchase-full-access', 'guard_name' => 'web'],
            ['id' => 43, 'name' => 'vehicle-full-access', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                $role
            );
        }
    }
}