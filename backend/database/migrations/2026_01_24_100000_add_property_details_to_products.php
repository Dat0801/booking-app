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
        Schema::table('products', function (Blueprint $table) {
            $table->string('location')->nullable()->after('image_url');
            $table->decimal('rating', 3, 2)->nullable()->default(0)->after('location');
            $table->integer('review_count')->nullable()->default(0)->after('rating');
            $table->json('gallery')->nullable()->after('review_count');
            $table->json('amenities')->nullable()->after('gallery');
            $table->json('reviews')->nullable()->after('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['location', 'rating', 'review_count', 'gallery', 'amenities', 'reviews']);
        });
    }
};
