<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // For tender committee path (مسار 2)
            $table->json('invoice_attachments')->nullable()->after('council_decision_files');
            $table->string('stock_entry_status')->nullable()->after('council_decision_status'); // pending, completed, rejected
            
            // For rent/repair path (مسار 3)
            $table->boolean('finance_signature')->default(false)->after('finance_transferred');
            $table->json('rent_repair_attachments')->nullable()->after('finance_signature');
            $table->string('rent_repair_status')->nullable()->after('rent_repair_attachments'); // approved, rejected
            $table->text('rent_repair_rejection_reason')->nullable()->after('rent_repair_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_attachments',
                'stock_entry_status',
                'finance_signature',
                'rent_repair_attachments',
                'rent_repair_status',
                'rent_repair_rejection_reason'
            ]);
        });
    }
};
