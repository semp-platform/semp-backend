<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nominations', function (Blueprint $table) {

            $table->foreignId('lcda_id')
                ->nullable()
                ->after('ward_id')
                ->constrained('lcdas')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('nominations', function (Blueprint $table) {

            $table->dropConstrainedForeignId('lcda_id');

        });
    }
};
