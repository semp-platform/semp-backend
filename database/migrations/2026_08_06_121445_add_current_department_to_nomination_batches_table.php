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

            /*
            |--------------------------------------------------------------------------
            | Workflow Ownership
            |--------------------------------------------------------------------------
            */

            $table->string('current_department')
                ->nullable()
                ->after('status')
                ->index();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nomination_batches', function (Blueprint $table) {

            $table->dropIndex(['current_department']);

            $table->dropColumn('current_department');

        });
    }
};
