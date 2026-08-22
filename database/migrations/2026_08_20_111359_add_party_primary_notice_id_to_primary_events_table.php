<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('primary_events', function (Blueprint $table) {
            $table->foreignId('party_primary_notice_id')
                ->nullable()
                ->after('id')
                ->unique()
                ->constrained('party_primary_notices')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('primary_events', function (Blueprint $table) {
            $table->dropForeign(['party_primary_notice_id']);
            $table->dropUnique(['party_primary_notice_id']);
            $table->dropColumn('party_primary_notice_id');
        });
    }
};
