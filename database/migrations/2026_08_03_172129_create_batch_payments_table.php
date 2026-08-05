<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('nomination_batch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('political_party_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('payment_reference')
                ->unique();

            $table->string('gateway_reference')
                ->nullable();

            $table->decimal('amount', 12, 2);

            $table->string('currency', 3)
                ->default('NGN');

            $table->string('gateway')
                ->default('paystack');

            $table->string('status', 20)
                ->default('pending');

            $table->timestamp('paid_at')
                ->nullable();

            $table->json('gateway_response')
                ->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_payments');
    }
};
