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
    Schema::create('political_party_users', function (Blueprint $table) {
        $table->id();

        $table->foreignId('political_party_id')
            ->constrained('political_parties')
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->boolean('is_active')->default(true);

        $table->timestamps();

        $table->unique(
            ['political_party_id', 'user_id'],
            'political_party_users_party_user_unique'
        );
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('political_party_users');
}
};
