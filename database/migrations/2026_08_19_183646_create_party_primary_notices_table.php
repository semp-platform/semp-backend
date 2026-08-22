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
    Schema::create('party_primary_notices', function (Blueprint $table) {
        $table->id();

        $table->foreignId('political_party_id')
            ->constrained('political_parties')
            ->restrictOnDelete();

        $table->foreignId('election_id')
            ->constrained('elections')
            ->restrictOnDelete();

        $table->foreignId('position_id')
            ->constrained('positions')
            ->restrictOnDelete();

        $table->string('primary_type', 30);

        $table->date('scheduled_date');
        $table->time('scheduled_time')->nullable();

        $table->string('venue')->nullable();

        $table->foreignId('lga_id')
            ->nullable()
            ->constrained('lgas')
            ->nullOnDelete();

        $table->foreignId('lcda_id')
            ->nullable()
            ->constrained('lcdas')
            ->nullOnDelete();

        $table->foreignId('ward_id')
            ->nullable()
            ->constrained('wards')
            ->nullOnDelete();

        $table->string('status', 30)
            ->default('draft');

        $table->timestamp('submitted_at')->nullable();

        $table->foreignId('submitted_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('received_at')->nullable();

        $table->timestamp('reviewed_at')->nullable();

        $table->foreignId('reviewed_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->text('review_comment')->nullable();

        $table->timestamps();

        $table->index([
            'political_party_id',
            'election_id',
        ]);

        $table->index([
            'status',
            'scheduled_date',
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_primary_notices');
    }
};
