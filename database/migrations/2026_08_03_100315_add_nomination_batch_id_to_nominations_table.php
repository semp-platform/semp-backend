<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nominations', function (Blueprint $table) {

            $table->foreignId('nomination_batch_id')
                ->nullable()
                ->after('political_party_id')
                ->constrained('nomination_batches')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('nominations', function (Blueprint $table) {

            $table->dropConstrainedForeignId('nomination_batch_id');

        });
    }
};
