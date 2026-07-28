<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_type_id')
                ->constrained('election_types')
                ->restrictOnDelete();

            $table->foreignId('state_id')
                ->constrained('states')
                ->restrictOnDelete();

            $table->string('name', 150);

            $table->date('election_date');

            $table->string('status', 20)->default('draft');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
