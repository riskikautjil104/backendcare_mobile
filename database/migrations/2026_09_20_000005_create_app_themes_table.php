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
        Schema::create('app_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('primary_color', 10)->default('#087F7D');
            $table->string('primary_dark', 10)->default('#045453');
            $table->string('primary_light', 10)->default('#E6F6F6');
            $table->string('primary_accent', 10)->default('#00C9A7');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_themes');
    }
};
