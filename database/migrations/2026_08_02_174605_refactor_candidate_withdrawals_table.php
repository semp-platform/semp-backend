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
    Schema::table('candidate_withdrawals', function (Blueprint $table) {

        $table->foreignId('candidate_change_reason_id')
            ->nullable()
            ->after('nomination_id')
            ->constrained('candidate_change_reasons')
            ->restrictOnDelete();

        $table->text('remarks')
            ->nullable()
            ->after('candidate_change_reason_id');

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('candidate_withdrawals', function (Blueprint $table) {

        $table->renameColumn('remarks', 'reason');

        $table->dropConstrainedForeignId(
            'candidate_change_reason_id'
        );

    });
}
};
