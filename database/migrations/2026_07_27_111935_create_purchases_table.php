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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, purchase_order, tender_committee, rent_repair, rejected, approved_stock
            $table->json('files')->nullable();
            $table->boolean('president_signature')->default(false);
            $table->boolean('requester_signature')->default(false);
            $table->json('council_decision_files')->nullable();
            $table->string('council_decision_status')->nullable(); // rejected, approved_stock
            $table->boolean('finance_transferred')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};
