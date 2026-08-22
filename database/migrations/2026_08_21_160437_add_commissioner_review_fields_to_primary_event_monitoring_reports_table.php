<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('primary_event_monitoring_reports', function (Blueprint $table) {
            $table->string('review_status', 30)
                ->default('pending')
                ->after('status');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('review_status')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable()
                ->after('reviewed_by');

            $table->text('review_comment')
                ->nullable()
                ->after('reviewed_at');

            $table->index([
                'review_status',
                'submitted_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('primary_event_monitoring_reports', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);

            $table->dropIndex([
                'review_status',
                'submitted_at',
            ]);

            $table->dropColumn([
                'review_status',
                'reviewed_by',
                'reviewed_at',
                'review_comment',
            ]);
        });
    }
};
