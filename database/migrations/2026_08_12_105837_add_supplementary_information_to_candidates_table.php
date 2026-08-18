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
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('qualification')->nullable()->after('date_of_birth');

            $table->text('qualification_details')
                ->nullable()
                ->after('qualification');

            $table->boolean('is_pwd')
                ->default(false)
                ->after('qualification_details');

            $table->string('disability_type')
                ->nullable()
                ->after('is_pwd');

            $table->text('disability_details')
                ->nullable()
                ->after('disability_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'qualification',
                'qualification_details',
                'is_pwd',
                'disability_type',
                'disability_details',
            ]);
        });
    }
};
