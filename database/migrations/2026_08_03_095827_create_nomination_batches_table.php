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
        Schema::create('nomination_batches', function (Blueprint $table) {

    $table->id();

    $table->string('batch_number')->unique();

    $table->foreignId('election_id')
        ->constrained()
        ->restrictOnDelete();

    $table->foreignId('political_party_id')
        ->constrained()
        ->restrictOnDelete();

    $table->unsignedInteger('candidate_count')
        ->default(0);

    $table->decimal('total_nomination_fee', 12, 2)
        ->default(0);

    $table->decimal('amount_paid', 12, 2)
        ->default(0);

    $table->string('payment_status', 20)
        ->default('pending');

    $table->string('status', 20)
        ->default('draft');

    $table->foreignId('created_by')
    ->nullable()
    ->constrained('users')
    ->nullOnDelete();

    $table->foreignId('submitted_by')
    ->nullable()
    ->constrained('users')
    ->nullOnDelete();

    $table->timestamp('paid_at')->nullable();

    $table->timestamp('submitted_at')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomination_batches');
    }
};
