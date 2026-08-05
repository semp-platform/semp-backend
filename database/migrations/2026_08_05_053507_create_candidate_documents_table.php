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
        Schema::create('candidate_documents', function (Blueprint $table) {

            $table->id();

            $table->foreignId('candidate_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('document_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('original_name');

            $table->string('stored_name');

            $table->string('disk')
                ->default('private');

            $table->string('path');

            $table->string('mime_type');

            $table->unsignedBigInteger('file_size');

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('uploaded_at')
                ->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')
                ->nullable();

            $table->text('remarks')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | One active document per type per candidate
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'candidate_id',
                'document_type_id',
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_documents');
    }
};
