<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'qualification',
                'qualification_details',
                'is_pwd',
                'disability_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('qualification')->nullable();
            $table->text('qualification_details')->nullable();
            $table->boolean('is_pwd')->default(false);
            $table->string('disability_type')->nullable();
        });
    }
};
