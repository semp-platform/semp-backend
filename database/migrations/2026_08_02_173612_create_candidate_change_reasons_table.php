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
    Schema::create('candidate_change_reasons', function (Blueprint $table) {

        $table->id();

        $table->string('change_type', 30);

        $table->string('code', 50);

        $table->string('name', 150);

        $table->boolean('requires_document')
            ->default(false);

        $table->boolean('is_active')
            ->default(true);

        $table->timestamps();

        $table->unique([
            'change_type',
            'code',
        ]);

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_change_reasons');
    }
};
