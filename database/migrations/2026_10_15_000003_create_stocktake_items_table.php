<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocktake_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stocktake_id')->constrained('stocktakes')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            // الكمية الدفترية وقت إنشاء جلسة الجرد (لقطة تاريخية لا تتغير)
            $table->decimal('book_quantity', 12, 2)->default(0);
            // الكمية الفعلية بعد الجرد؛ null = لم يُجرَد الصنف بعد
            $table->decimal('counted_quantity', 12, 2)->nullable();
            $table->string('reason')->nullable(); // سبب الاختلاف إن وُجد
            $table->timestamps();
            $table->unique(['stocktake_id', 'item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocktake_items');
    }
};
