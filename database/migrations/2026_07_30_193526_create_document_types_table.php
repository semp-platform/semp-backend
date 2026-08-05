<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();

            $table->string('category');
            $table->string('workflow_stage');

            $table->text('description')->nullable();

            $table->boolean('required')->default(true);

            $table->boolean('allow_multiple_versions')->default(true);

            $table->unsignedInteger('display_order')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('category');
            $table->index('workflow_stage');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
