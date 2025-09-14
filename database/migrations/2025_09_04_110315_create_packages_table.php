<?php

// database/migrations/2025_09_04_000001_create_packages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedInteger('price');           // e.g., 25000, 45000, 70000, 90000
            $t->string('thumbnail')->nullable();    // main image
            $t->json('inclusions')->nullable();     // ["Tent ×1","Chairs ×20",...]
            $t->json('gallery')->nullable();        // ["url1","url2",...]
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
