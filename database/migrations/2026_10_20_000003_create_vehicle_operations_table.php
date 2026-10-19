<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_operations', function (Blueprint $table) {
            $table->id();
            // diesel | oil | maintenance
            $table->string('event_type', 20);
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->restrictOnDelete();
            $table->decimal('liters', 10, 2)->nullable();
            $table->decimal('maintenance_cost', 12, 2)->nullable();
            $table->date('operation_date');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            // منع تكرار الخصم عند إعادة إرسال الطلب
            $table->string('idempotency_key', 64)->nullable()->unique();
            $table->timestamps();
            $table->index(['vehicle_id', 'operation_date']);
            $table->index(['driver_id', 'operation_date']);
            $table->index('event_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_operations');
    }
};
