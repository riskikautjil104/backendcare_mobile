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
        Schema::create('hospital_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone_igd', 50)->default('(0921) 3121333');
            $table->string('phone_ambulance', 50)->default('119');
            $table->string('wa_cs', 50)->default('081143008889');
            $table->string('wa_pengaduan', 50)->nullable()->default('081234567890');
            $table->string('email', 100)->nullable()->default('info@chasanboesoirie.id');
            $table->string('address', 255)->default('Jl. Tanah Tinggi No. 1, Kota Ternate, Maluku Utara');
            $table->string('maps_url', 500)->nullable()->default('https://maps.google.com/?q=RSUD+Dr+H+Chasan+Boesoirie+Ternate');
            $table->string('complaint_url', 500)->nullable()->default('https://sp4n.lapor.go.id');
            $table->string('survey_url', 500)->nullable();
            $table->string('website_url', 500)->nullable()->default('https://chasanboesoirie.id');
            $table->text('operational_hours')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 255);
            $table->text('answer');
            $table->string('category', 50)->default('Umum');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('hospital_contacts');
    }
};
