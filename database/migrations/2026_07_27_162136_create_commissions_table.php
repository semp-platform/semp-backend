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
    Schema::create('commissions', function (Blueprint $table) {

        $table->id();

        // General Information
        $table->string('name');
        $table->string('acronym', 50);
        $table->string('motto')->nullable();
        $table->string('state');
        $table->text('headquarters_address');

        // Contact
        $table->string('email');
        $table->string('phone');
        $table->string('alternate_phone')->nullable();
        $table->string('website')->nullable();

        // Social Media
        $table->string('facebook')->nullable();
        $table->string('x')->nullable();
        $table->string('instagram')->nullable();
        $table->string('youtube')->nullable();

        // Branding
        $table->string('logo')->nullable();
        $table->string('seal')->nullable();
        $table->string('favicon')->nullable();

        $table->string('primary_color', 20)->default('#006838');
        $table->string('secondary_color', 20)->default('#FFFFFF');

        // Regional Settings
        $table->string('timezone')->default('Africa/Lagos');
        $table->string('currency')->default('NGN');
        $table->string('date_format')->default('d/m/Y');
        $table->string('time_format')->default('24');

        // Support
        $table->string('support_email')->nullable();
        $table->string('support_phone')->nullable();

        // Audit
$table->foreignId('created_by')
    ->nullable()
    ->constrained('users')
    ->nullOnDelete();

$table->foreignId('updated_by')
    ->nullable()
    ->constrained('users')
    ->nullOnDelete();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
