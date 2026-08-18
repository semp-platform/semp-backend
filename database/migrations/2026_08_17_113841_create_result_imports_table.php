<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_imports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('original_filename', 255);

            $table->string('status', 30)
                ->default('analysed');

            $table->unsignedInteger('worksheet_count')->default(0);
            $table->unsignedInteger('polling_unit_count')->default(0);
            $table->unsignedInteger('result_entry_count')->default(0);

            $table->timestamp('analysed_at')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index([
                'election_id',
                'position_id',
            ]);

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_imports');
    }
};
