<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {

            $table->date('nomination_open_date')
                ->nullable()
                ->after('election_date');

            $table->date('nomination_close_date')
                ->nullable()
                ->after('nomination_open_date');

            $table->date('screening_date')
                ->nullable()
                ->after('nomination_close_date');

            $table->date('appeal_deadline')
                ->nullable()
                ->after('screening_date');

            $table->date('result_declaration_date')
                ->nullable()
                ->after('appeal_deadline');

        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {

            $table->dropColumn([
                'nomination_open_date',
                'nomination_close_date',
                'screening_date',
                'appeal_deadline',
                'result_declaration_date',
            ]);

        });
    }
};
