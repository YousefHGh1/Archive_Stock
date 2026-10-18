 موجودة بالمخزن
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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->json('purchase_attachments')->nullable();
            $table->date('purchase_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('description')->nullable();
            $table->boolean('purchases_signature')->default(false);
            $table->boolean('finance_signature')->default(false);
            $table->boolean('requester_signature')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_orders');
    }
};