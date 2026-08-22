<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('primary_event_monitoring_report_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('primary_event_monitoring_report_id')
                ->constrained('primary_event_monitoring_reports')
                ->cascadeOnDelete();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('category', 20); // photo | document

            $table->string('file_path');

            $table->string('original_name');

            $table->string('mime_type', 150);

            $table->unsignedBigInteger('file_size');

            $table->timestamps();

            $table->index([
                'primary_event_monitoring_report_id',
                'category',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'primary_event_monitoring_report_attachments'
        );
    }
};
