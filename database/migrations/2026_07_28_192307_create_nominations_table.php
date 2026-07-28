<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nominations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('political_party_id')
                ->constrained('political_parties')
                ->restrictOnDelete();

            $table->foreignId('candidate_id')
                ->constrained('candidates')
                ->restrictOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->foreignId('lga_id')
                ->nullable()
                ->constrained('lgas')
                ->restrictOnDelete();

            $table->foreignId('ward_id')
                ->nullable()
                ->constrained('wards')
                ->restrictOnDelete();

            $table->string('status', 20)->default('draft');

            $table->timestamps();

            $table->unique([
                'election_id',
                'candidate_id',
                'position_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nominations');
    }
};
