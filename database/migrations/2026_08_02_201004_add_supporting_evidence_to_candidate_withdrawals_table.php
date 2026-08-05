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

        $table->string('supporting_evidence_path')
            ->nullable()
            ->after('remarks');

    });
}

public function down(): void
{
    Schema::table('candidate_withdrawals', function (Blueprint $table) {

        $table->dropColumn('supporting_evidence_path');

    });
}
};
