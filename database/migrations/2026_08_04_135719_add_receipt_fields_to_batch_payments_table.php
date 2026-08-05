<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('batch_payments', function (Blueprint $table) {

            $table->string('receipt_number')
                ->nullable()
                ->unique()
                ->after('payment_reference');

            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('status');

            $table->timestamp('confirmed_at')
                ->nullable()
                ->after('confirmed_by');

            $table->timestamp('receipt_generated_at')
                ->nullable()
                ->after('confirmed_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_payments', function (Blueprint $table) {

            $table->dropForeign(['confirmed_by']);

            $table->dropColumn([
                'receipt_number',
                'confirmed_by',
                'confirmed_at',
                'receipt_generated_at',
            ]);

        });
    }
};
