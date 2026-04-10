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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page')->unique();

            // 🔍 KEYWORD RESEARCH
            $table->text('keywords')->nullable();

            // 🏷️ PAGE TITLES
            $table->string('page_title');

            // 📝 META DESCRIPTIONS
            $table->text('meta_description')->nullable();

            // 🏷️ META TAGS
            $table->string('meta_author')->nullable();
            $table->string('meta_robots')->default('index, follow');

            // 🔠 HEADING TAGS
            $table->string('heading_h1')->nullable();

            // 🔗 URL NAMING (CANONICAL)
            $table->string('canonical_url')->nullable();

            // 🖼️ IMAGE SEO
            $table->string('image_alt')->nullable();

            // 🗺️ SITEMAP
            $table->boolean('enable_sitemap')->default(true);

            // 🤖 ROBOTS FILE
            $table->boolean('enable_robots')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
