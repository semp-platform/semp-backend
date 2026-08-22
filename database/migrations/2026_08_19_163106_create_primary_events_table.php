<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('primary_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('political_party_id')
                ->constrained('political_parties')
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

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

            $table->string('primary_type', 30);

            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();

            $table->string('venue')->nullable();

            $table->timestamp('notice_received_at')->nullable();

            $table->string('notice_status', 30)
                ->default('pending');

            $table->string('status', 30)
                ->default('scheduled');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'election_id',
                'political_party_id',
            ]);

            $table->index([
                'scheduled_date',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('primary_events');
    }
};
