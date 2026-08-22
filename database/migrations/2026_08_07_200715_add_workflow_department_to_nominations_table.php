<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nominations', function (Blueprint $table) {
            $table->string('current_department', 50)
                ->nullable()
                ->after('status');

            $table->string('workflow_status', 50)
                ->nullable()
                ->after('current_department');

            $table->index('current_department');
        });
    }

    public function down(): void
    {
        Schema::table('nominations', function (Blueprint $table) {
            $table->dropIndex([
                'nominations_current_department_index',
            ]);

            $table->dropColumn([
                'current_department',
                'workflow_status',
            ]);
        });
    }
};
