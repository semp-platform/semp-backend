<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->boolean('has_disability')
                ->default(false)
                ->after('gender');

            $table->string('disability_details', 255)
                ->nullable()
                ->after('has_disability');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'has_disability',
                'disability_details',
            ]);
        });
    }
};
