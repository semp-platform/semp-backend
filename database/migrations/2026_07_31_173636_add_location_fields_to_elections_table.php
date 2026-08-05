<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {

            $table->foreignId('lga_id')
                ->nullable()
                ->after('state_id')
                ->constrained('lgas')
                ->nullOnDelete();

            $table->foreignId('ward_id')
                ->nullable()
                ->after('lga_id')
                ->constrained('wards')
                ->nullOnDelete();

            $table->foreignId('lcda_id')
                ->nullable()
                ->after('ward_id')
                ->constrained('lcdas')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {

            $table->dropConstrainedForeignId('lcda_id');
            $table->dropConstrainedForeignId('ward_id');
            $table->dropConstrainedForeignId('lga_id');

        });
    }
};
