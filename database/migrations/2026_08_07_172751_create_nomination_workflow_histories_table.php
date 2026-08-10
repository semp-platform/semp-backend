<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomination_workflow_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('nomination_id')
                ->constrained('nominations')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Workflow location
            |--------------------------------------------------------------------------
            */

            $table->string('from_department')->nullable();
            $table->string('to_department')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Action
            |--------------------------------------------------------------------------
            */

            $table->string('action');

            /*
            |--------------------------------------------------------------------------
            | Officer responsible for the action
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Explanation attached to the action
            |--------------------------------------------------------------------------
            */

            $table->text('comment')->nullable();
            $table->text('reason')->nullable();

            $table->timestamps();

            $table->index([
                'nomination_id',
                'created_at',
            ]);

            $table->index('from_department');
            $table->index('to_department');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomination_workflow_histories');
    }
};
