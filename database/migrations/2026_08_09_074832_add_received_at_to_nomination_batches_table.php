<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nomination_batches', function (Blueprint $table) {
            $table->timestamp('received_at')
                ->nullable()
                ->after('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomination_batches', function (Blueprint $table) {
            $table->dropColumn('received_at');
        });
    }
};
