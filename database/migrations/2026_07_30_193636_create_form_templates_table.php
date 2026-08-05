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
        Schema::create('form_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');

            $table->string('version')
                ->default('1.0');

            $table->string('file_path');

            $table->string('mime_type')->nullable();

            $table->unsignedBigInteger('file_size')->nullable();

            $table->date('effective_date')->nullable();

            $table->boolean('is_active')->default(true);

            $table->foreignId('uploaded_by')
                ->constrained('users');

            $table->timestamps();

            $table->index('is_active');
            $table->index('effective_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_templates');
    }
};
