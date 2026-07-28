<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            $table->text('nin_encrypted');

            $table->string('nin_hash', 64)->unique();

            $table->string('first_name', 100);

            $table->string('middle_name', 100)->nullable();

            $table->string('last_name', 100);

            $table->string('gender', 20);

            $table->date('date_of_birth');

            $table->timestamp('nin_verified_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
