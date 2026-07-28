<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->decimal('nomination_fee', 15, 2)->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['election_id', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_positions');
    }
};
