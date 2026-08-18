<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_contents', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);

            $table->string('slug', 255)
                ->unique();

            $table->string('type', 30)
                ->default('news');

            $table->text('summary')
                ->nullable();

            $table->longText('content');

            $table->string('image_path')
                ->nullable();

            $table->string('attachment_path')
                ->nullable();

            $table->timestamp('published_at')
                ->nullable();

            $table->timestamp('display_until')
                ->nullable();

            $table->boolean('is_published')
                ->default(false);

            $table->boolean('is_featured')
                ->default(false);

            $table->boolean('is_ticker')
                ->default(false);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'type',
                'is_published',
                'published_at',
            ]);

            $table->index([
                'is_featured',
                'is_published',
            ]);

            $table->index([
                'is_ticker',
                'is_published',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_contents');
    }
};
