<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_withdrawals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('nomination_id')
                ->constrained('nominations')
                ->restrictOnDelete();

            $table->foreignId('political_party_id')
                ->constrained('political_parties')
                ->restrictOnDelete();

            $table->text('reason');

            $table->string('status', 20)
                ->default('draft');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_withdrawals');
    }
};
