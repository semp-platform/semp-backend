<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('primary_event_monitoring_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('primary_event_id')
                ->unique()
                ->constrained('primary_events')
                ->cascadeOnDelete();

            $table->foreignId('monitor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->string('attendance_status', 30)->nullable();

            $table->unsignedInteger('accredited_voters')->nullable();
            $table->unsignedInteger('votes_cast')->nullable();

            $table->text('observations')->nullable();
            $table->text('incidents')->nullable();
            $table->text('recommendations')->nullable();

            $table->string('status', 30)
                ->default('draft');

            $table->timestamps();

            $table->index([
                'monitor_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('primary_event_monitoring_reports');
    }
};
