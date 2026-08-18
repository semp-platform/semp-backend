<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('result_import_id')
                ->constrained('result_imports')
                ->cascadeOnDelete();

            $table->foreignId('lga_id')
                ->constrained('lgas')
                ->restrictOnDelete();

            $table->foreignId('ward_id')
                ->nullable()
                ->constrained('wards')
                ->restrictOnDelete();

            $table->foreignId('political_party_id')
                ->constrained('political_parties')
                ->restrictOnDelete();

            $table->string('polling_unit_code', 50);

            $table->string('polling_unit_name', 255)->nullable();

            $table->unsignedInteger('votes')->default(0);

            $table->timestamps();

            $table->index([
                'result_import_id',
                'lga_id',
                'ward_id',
            ]);

            $table->index([
                'result_import_id',
                'political_party_id',
            ]);

            $table->index('polling_unit_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_entries');
    }
};
