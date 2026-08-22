<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('primary_event_monitor_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('primary_event_id')
                ->constrained('primary_events')
                ->cascadeOnDelete();

            $table->foreignId('monitor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->useCurrent();

            $table->string('status', 30)->default('assigned');

            $table->text('instructions')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index([
                'primary_event_id',
                'monitor_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('primary_event_monitor_assignments');
    }
};
