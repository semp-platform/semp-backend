<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetTestData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'semp:reset-test-data';

    /**
     * The console command description.
     */
    protected $description = 'Delete all transactional test data while preserving master data.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->confirm(
            'This will permanently delete all transactional test data. Continue?'
        )) {
            return self::SUCCESS;
        }

        DB::transaction(function () {

            $this->line('');
            $this->info('Resetting SEMP test data...');
            $this->line('');

            /*
            |--------------------------------------------------------------------------
            | Delete in dependency order
            |--------------------------------------------------------------------------
            */

            $this->deleteTable(
                'candidate_documents'
            );

            $this->deleteTable(
                'candidate_replacements'
            );

            $this->deleteTable(
                'candidate_withdrawals'
            );

            $this->deleteTable(
                'batch_payments'
            );

            $this->deleteTable(
                'nominations'
            );

            $this->deleteTable(
                'nomination_batches'
            );

            $this->deleteTable(
                'candidates'
            );

            $this->line('');

            $this->info(
                'Master data preserved.'
            );

            $this->info(
                'Test data reset completed successfully.'
            );

        });

        return self::SUCCESS;
    }

    /**
     * Delete all records from a table.
     */
    /**
 * Truncate a table and reset its identity.
 */
protected function deleteTable(
    string $table
): void {

    if (! DB::getSchemaBuilder()->hasTable($table)) {

        return;

    }

    $count = DB::table($table)->count();

    DB::statement(sprintf(
        'TRUNCATE TABLE "%s" RESTART IDENTITY CASCADE',
        $table
    ));

    $this->info(sprintf(
        '✓ %-30s %5d record(s)',
        $table,
        $count
    ));

}
}
