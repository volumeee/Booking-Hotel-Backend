<?php

// database/migrations/xxxx_xx_xx_create_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50)->nullable();
            $table->string('tripay_reference')->nullable();
            $table->string('tripay_merchant_ref')->nullable();
            $table->string('tripay_payment_method', 50)->nullable();
            $table->string('tripay_pay_code')->nullable();
            $table->decimal('tripay_amount_received', 10, 2)->nullable();
            $table->decimal('tripay_fee_merchant', 10, 2)->nullable();
            $table->decimal('tripay_fee_customer', 10, 2)->nullable();
            $table->decimal('tripay_total_fee', 10, 2)->nullable();
            $table->string('tripay_status', 20)->nullable();
            $table->timestamp('tripay_expired_time')->nullable();
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
