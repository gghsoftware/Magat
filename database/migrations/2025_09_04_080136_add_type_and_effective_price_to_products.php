<?php

// database/migrations/2025_09_04_000001_add_type_and_effective_price_to_products.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('type', ['simple', 'package', 'service', 'addon'])->default('simple')->after('category_id')->index();
            $table->decimal('effective_price', 10, 2)->nullable()->after('price')->index();
        });

        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price_override', 10, 2)->nullable();
            $table->boolean('is_optional')->default(false); // add-ons = optional
            $table->timestamps();

            $table->unique(['package_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_items');
        Schema::table('products', fn(Blueprint $t) => $t->dropColumn(['type', 'effective_price']));
    }
};
