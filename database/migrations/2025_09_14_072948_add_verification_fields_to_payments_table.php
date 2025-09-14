<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_verification_fields_to_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('payments', function (Blueprint $t) {
            $t->string('proof_path')->nullable()->after('payment_status');
            $t->enum('verification_status', ['pending','approved','rejected'])->default('pending')->after('proof_path');
            $t->unsignedBigInteger('verified_by')->nullable()->after('verification_status');
            $t->timestamp('verified_at')->nullable()->after('verified_by');
            $t->text('verification_notes')->nullable()->after('verified_at');
        });
    }
    public function down(): void {
        Schema::table('payments', function (Blueprint $t) {
            $t->dropColumn(['proof_path','verification_status','verified_by','verified_at','verification_notes']);
        });
    }
};
