<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stocktakes', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique();
            $table->date('stocktake_date');
            $table->string('status')->default('draft'); // draft, approved
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocktakes');
    }
};
