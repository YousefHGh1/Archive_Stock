<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify items table
        DB::statement('ALTER TABLE items MODIFY COLUMN open_balance DECIMAL(10, 2)');
        DB::statement('ALTER TABLE items MODIFY COLUMN low_limit DECIMAL(10, 2)');
        DB::statement('ALTER TABLE items MODIFY COLUMN balance DECIMAL(10, 2)');

        // Modify invoice_export_products table
        DB::statement('ALTER TABLE invoice_export_products MODIFY COLUMN quantity DECIMAL(10, 2)');

        // Modify diesels table
        DB::statement('ALTER TABLE diesels MODIFY COLUMN quantity DECIMAL(10, 2)');

        // Modify diesel_exports table
        DB::statement('ALTER TABLE diesel_exports MODIFY COLUMN quantity DECIMAL(10, 2)');

        // Modify custody_products table
        DB::statement('ALTER TABLE custody_products MODIFY COLUMN quantity DECIMAL(10, 2)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert items table
        DB::statement('ALTER TABLE items MODIFY COLUMN open_balance INT');
        DB::statement('ALTER TABLE items MODIFY COLUMN low_limit INT');
        DB::statement('ALTER TABLE items MODIFY COLUMN balance INT');

        // Revert invoice_export_products table
        DB::statement('ALTER TABLE invoice_export_products MODIFY COLUMN quantity INT');

        // Revert diesels table
        DB::statement('ALTER TABLE diesels MODIFY COLUMN quantity INT');

        // Revert diesel_exports table
        DB::statement('ALTER TABLE diesel_exports MODIFY COLUMN quantity INT');

        // Revert custody_products table
        DB::statement('ALTER TABLE custody_products MODIFY COLUMN quantity INT');
    }
};
