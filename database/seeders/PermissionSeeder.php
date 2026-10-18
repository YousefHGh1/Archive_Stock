<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // ======================== Original Permissions ========================
            ['id' => 1, 'name' => 'units', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'categories', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'items', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'stores', 'guard_name' => 'web'],
            ['id' => 5, 'name' => 'suppliers', 'guard_name' => 'web'],
            ['id' => 9, 'name' => 'users', 'guard_name' => 'web'],
            ['id' => 10, 'name' => 'permissions', 'guard_name' => 'web'],
            ['id' => 11, 'name' => 'roles', 'guard_name' => 'web'],
            ['id' => 12, 'name' => 'diesels', 'guard_name' => 'web'],
            ['id' => 14, 'name' => 'archive', 'guard_name' => 'web'],
            ['id' => 15, 'name' => 'legal', 'guard_name' => 'web'],
            ['id' => 16, 'name' => 'computer', 'guard_name' => 'web'],
            ['id' => 17, 'name' => 'jibaya', 'guard_name' => 'web'],
            ['id' => 18, 'name' => 'censorship', 'guard_name' => 'web'],
            ['id' => 19, 'name' => 'admin', 'guard_name' => 'web'],
            ['id' => 23, 'name' => 'report', 'guard_name' => 'web'],
            ['id' => 24, 'name' => 'archivecore', 'guard_name' => 'web'],
            ['id' => 25, 'name' => 'HR', 'guard_name' => 'web'],
            ['id' => 26, 'name' => 'stock', 'guard_name' => 'web'],
            ['id' => 27, 'name' => 'invoices', 'guard_name' => 'web'],
            ['id' => 28, 'name' => 'types', 'guard_name' => 'web'],
            ['id' => 29, 'name' => 'supplier_items', 'guard_name' => 'web'],
            ['id' => 30, 'name' => 'invoice_exports', 'guard_name' => 'web'],
            ['id' => 31, 'name' => 'custody', 'guard_name' => 'web'],
            ['id' => 33, 'name' => 'currency', 'guard_name' => 'web'],
            ['id' => 34, 'name' => 'court', 'guard_name' => 'web'],
            ['id' => 37, 'name' => 'user_admin', 'guard_name' => 'web'],
            ['id' => 38, 'name' => 'sections', 'guard_name' => 'web'],
            ['id' => 39, 'name' => 'employee', 'guard_name' => 'web'],
            // ======================== Granular Permissions (Inventory Modules) ========================
            ['id' => 40, 'name' => 'units-create', 'guard_name' => 'web'],
            ['id' => 41, 'name' => 'units-read', 'guard_name' => 'web'],
            ['id' => 42, 'name' => 'units-update', 'guard_name' => 'web'],
            ['id' => 43, 'name' => 'units-delete', 'guard_name' => 'web'],
            ['id' => 44, 'name' => 'categories-create', 'guard_name' => 'web'],
            ['id' => 45, 'name' => 'categories-read', 'guard_name' => 'web'],
            ['id' => 46, 'name' => 'categories-update', 'guard_name' => 'web'],
            ['id' => 47, 'name' => 'categories-delete', 'guard_name' => 'web'],
            ['id' => 48, 'name' => 'items-create', 'guard_name' => 'web'],
            ['id' => 49, 'name' => 'items-read', 'guard_name' => 'web'],
            ['id' => 50, 'name' => 'items-update', 'guard_name' => 'web'],
            ['id' => 51, 'name' => 'items-delete', 'guard_name' => 'web'],
            ['id' => 52, 'name' => 'supplier_items-create', 'guard_name' => 'web'],
            ['id' => 53, 'name' => 'supplier_items-read', 'guard_name' => 'web'],
            ['id' => 54, 'name' => 'supplier_items-update', 'guard_name' => 'web'],
            ['id' => 55, 'name' => 'supplier_items-delete', 'guard_name' => 'web'],
            ['id' => 56, 'name' => 'invoices-create', 'guard_name' => 'web'],
            ['id' => 57, 'name' => 'invoices-read', 'guard_name' => 'web'],
            ['id' => 58, 'name' => 'invoices-update', 'guard_name' => 'web'],
            ['id' => 59, 'name' => 'invoices-delete', 'guard_name' => 'web'],
            ['id' => 60, 'name' => 'invoice_exports-create', 'guard_name' => 'web'],
            ['id' => 61, 'name' => 'invoice_exports-read', 'guard_name' => 'web'],
            ['id' => 62, 'name' => 'invoice_exports-update', 'guard_name' => 'web'],
            ['id' => 63, 'name' => 'invoice_exports-delete', 'guard_name' => 'web'],
            ['id' => 64, 'name' => 'custody-create', 'guard_name' => 'web'],
            ['id' => 65, 'name' => 'custody-read', 'guard_name' => 'web'],
            ['id' => 66, 'name' => 'custody-update', 'guard_name' => 'web'],
            ['id' => 67, 'name' => 'custody-delete', 'guard_name' => 'web'],
            ['id' => 68, 'name' => 'currency-create', 'guard_name' => 'web'],
            ['id' => 69, 'name' => 'currency-read', 'guard_name' => 'web'],
            ['id' => 70, 'name' => 'currency-update', 'guard_name' => 'web'],
            ['id' => 71, 'name' => 'currency-delete', 'guard_name' => 'web'],
            ['id' => 72, 'name' => 'employee-create', 'guard_name' => 'web'],
            ['id' => 73, 'name' => 'employee-read', 'guard_name' => 'web'],
            ['id' => 74, 'name' => 'employee-update', 'guard_name' => 'web'],
            ['id' => 75, 'name' => 'employee-delete', 'guard_name' => 'web'],
            ['id' => 76, 'name' => 'diesels-create', 'guard_name' => 'web'],
            ['id' => 77, 'name' => 'diesels-read', 'guard_name' => 'web'],
            ['id' => 78, 'name' => 'diesels-update', 'guard_name' => 'web'],
            ['id' => 79, 'name' => 'diesels-delete', 'guard_name' => 'web'],
            ['id' => 80, 'name' => 'dieselexport-create', 'guard_name' => 'web'],
            ['id' => 81, 'name' => 'dieselexport-read', 'guard_name' => 'web'],
            ['id' => 82, 'name' => 'dieselexport-update', 'guard_name' => 'web'],
            ['id' => 83, 'name' => 'dieselexport-delete', 'guard_name' => 'web'],
            ['id' => 84, 'name' => 'report-create', 'guard_name' => 'web'],
            ['id' => 85, 'name' => 'report-read', 'guard_name' => 'web'],
            ['id' => 86, 'name' => 'report-update', 'guard_name' => 'web'],
            ['id' => 87, 'name' => 'report-delete', 'guard_name' => 'web'],
            ['id' => 88, 'name' => 'sections-create', 'guard_name' => 'web'],
            ['id' => 89, 'name' => 'sections-read', 'guard_name' => 'web'],
            ['id' => 90, 'name' => 'sections-update', 'guard_name' => 'web'],
            ['id' => 91, 'name' => 'sections-delete', 'guard_name' => 'web'],
            ['id' => 92, 'name' => 'stores-create', 'guard_name' => 'web'],
            ['id' => 93, 'name' => 'stores-read', 'guard_name' => 'web'],
            ['id' => 94, 'name' => 'stores-update', 'guard_name' => 'web'],
            ['id' => 95, 'name' => 'stores-delete', 'guard_name' => 'web'],
            // ======================== Purchase Permissions ========================
            ['id' => 96, 'name' => 'purchase', 'guard_name' => 'web'],
            ['id' => 97, 'name' => 'purchases-create', 'guard_name' => 'web'],
            ['id' => 98, 'name' => 'purchases-read', 'guard_name' => 'web'],
            ['id' => 99, 'name' => 'purchases-update', 'guard_name' => 'web'],
            ['id' => 100, 'name' => 'purchases-delete', 'guard_name' => 'web'],
            // ======================== Archive Permissions ========================
            ['id' => 101, 'name' => 'archive-create', 'guard_name' => 'web'],
            ['id' => 102, 'name' => 'archive-read', 'guard_name' => 'web'],
            ['id' => 103, 'name' => 'archive-update', 'guard_name' => 'web'],
            ['id' => 104, 'name' => 'archive-delete', 'guard_name' => 'web'],
            // ======================== Legal Permissions ========================
            ['id' => 105, 'name' => 'legal-create', 'guard_name' => 'web'],
            ['id' => 106, 'name' => 'legal-read', 'guard_name' => 'web'],
            ['id' => 107, 'name' => 'legal-update', 'guard_name' => 'web'],
            ['id' => 108, 'name' => 'legal-delete', 'guard_name' => 'web'],
            // ======================== Computer Permissions ========================
            ['id' => 109, 'name' => 'computer-create', 'guard_name' => 'web'],
            ['id' => 110, 'name' => 'computer-read', 'guard_name' => 'web'],
            ['id' => 111, 'name' => 'computer-update', 'guard_name' => 'web'],
            ['id' => 112, 'name' => 'computer-delete', 'guard_name' => 'web'],
            // ======================== Censorship Permissions ========================
            ['id' => 113, 'name' => 'censorship-create', 'guard_name' => 'web'],
            ['id' => 114, 'name' => 'censorship-read', 'guard_name' => 'web'],
            ['id' => 115, 'name' => 'censorship-update', 'guard_name' => 'web'],
            ['id' => 116, 'name' => 'censorship-delete', 'guard_name' => 'web'],
            // ======================== Jibaya Permissions ========================
            ['id' => 117, 'name' => 'jibaya-create', 'guard_name' => 'web'],
            ['id' => 118, 'name' => 'jibaya-read', 'guard_name' => 'web'],
            ['id' => 119, 'name' => 'jibaya-update', 'guard_name' => 'web'],
            ['id' => 120, 'name' => 'jibaya-delete', 'guard_name' => 'web'],
            // ======================== Suppliers Permissions ========================
            ['id' => 121, 'name' => 'suppliers-create', 'guard_name' => 'web'],
            ['id' => 122, 'name' => 'suppliers-read', 'guard_name' => 'web'],
            ['id' => 123, 'name' => 'suppliers-update', 'guard_name' => 'web'],
            ['id' => 124, 'name' => 'suppliers-delete', 'guard_name' => 'web'],
            // ======================== Users Permissions ========================
            ['id' => 125, 'name' => 'users-create', 'guard_name' => 'web'],
            ['id' => 126, 'name' => 'users-read', 'guard_name' => 'web'],
            ['id' => 127, 'name' => 'users-update', 'guard_name' => 'web'],
            ['id' => 128, 'name' => 'users-delete', 'guard_name' => 'web'],
            // ======================== Section-based Permissions ========================
            ['id' => 129, 'name' => 'view-own-section', 'guard_name' => 'web'],
            ['id' => 130, 'name' => 'view-own-subsection', 'guard_name' => 'web'],
            ['id' => 131, 'name' => 'manage-own-section', 'guard_name' => 'web'],
            ['id' => 132, 'name' => 'manage-own-subsection', 'guard_name' => 'web'],
            ['id' => 133, 'name' => 'view-all-sections', 'guard_name' => 'web'],
            ['id' => 134, 'name' => 'manage-all-sections', 'guard_name' => 'web'],
        ];

        DB::table('permissions')->insertOrIgnore($permissions);

        foreach (['purchase', 'purchases-create', 'purchases-read', 'purchases-update', 'purchases-delete'] as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                ['name' => $permission, 'guard_name' => 'web']
            );
        }
    }
}
