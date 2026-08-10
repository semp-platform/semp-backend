<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_document_review_requests', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Candidate / nomination
            |--------------------------------------------------------------------------
            */

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->cascadeOnDelete();

            $table->foreignId('nomination_id')
                ->constrained('nominations')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Document being reviewed
            |--------------------------------------------------------------------------
            */

            $table->foreignId('candidate_document_id')
                ->constrained('candidate_documents')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Officer who requested the replacement
            |--------------------------------------------------------------------------
            */

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('department');

            /*
            |--------------------------------------------------------------------------
            | Request details
            |--------------------------------------------------------------------------
            */

            $table->string('status')->default('requested');

            $table->text('reason')->nullable();

            $table->text('comment')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Resolution
            |--------------------------------------------------------------------------
            */

            $table->timestamp('requested_at')->nullable();

            $table->timestamp('resolved_at')->nullable();

            $table->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'nomination_id',
                'status',
            ]);

            $table->index([
                'candidate_document_id',
                'status',
            ]);

            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_document_review_requests');
    }
};
