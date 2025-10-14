<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('advert_id')->nullable()->constrained('adverts')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->enum('gateway', ['paystack', 'flutterwave']);
            $table->string('reference')->unique();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
