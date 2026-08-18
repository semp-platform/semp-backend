<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->foreignId('nomination_id')
                ->constrained('nominations')
                ->restrictOnDelete();

            $table->foreignId('lga_id')
                ->constrained('lgas')
                ->restrictOnDelete();

            $table->foreignId('ward_id')
                ->nullable()
                ->constrained('wards')
                ->restrictOnDelete();

            $table->unsignedInteger('total_votes')->default(0);

            $table->boolean('is_winner')->default(false);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->unique([
                'election_id',
                'nomination_id',
            ]);

            $table->index([
                'election_id',
                'position_id',
                'lga_id',
                'ward_id',
            ]);

            $table->index([
                'election_id',
                'lga_id',
                'is_winner',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_results');
    }
};
