<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_withdrawals', function (Blueprint $table) {

            /*
             * The original nomination being withdrawn is already stored
             * in nomination_id.
             *
             * Once the withdrawal is approved, this stores the new
             * nomination created for the replacement candidate.
             */
            $table->foreignId('replacement_nomination_id')
                ->nullable()
                ->after('nomination_id')
                ->constrained('nominations')
                ->nullOnDelete();

            /*
             * Commissioner who approved or rejected the request.
             */
            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();

            /*
             * Date/time the Commissioner made the decision.
             */
            $table->timestamp('reviewed_at')
                ->nullable()
                ->after('rejected_at');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_withdrawals', function (Blueprint $table) {

            $table->dropForeign([
                'replacement_nomination_id',
            ]);

            $table->dropForeign([
                'reviewed_by',
            ]);

            $table->dropColumn([
                'replacement_nomination_id',
                'reviewed_by',
                'reviewed_at',
            ]);
        });
    }
};
