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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('min_amount', 10, 2)->nullable()->comment('Minimum order amount to apply');
            $table->decimal('max_discount', 10, 2)->nullable()->comment('Maximum discount amount (for percentage type)');
            $table->integer('usage_limit')->nullable()->comment('Maximum number of times coupon can be used');
            $table->integer('used_count')->default(0);
            $table->integer('user_limit')->nullable()->comment('Maximum times a single user can use this coupon');
            $table->enum('applicable_to', ['all', 'products', 'services', 'categories'])->default('all');
            $table->json('applicable_ids')->nullable()->comment('IDs of products/categories this coupon applies to');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
