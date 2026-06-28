<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('receipt_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 7)->unique();
            $table->boolean('is_used')->default(false);
            $table->foreignId('used_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('receipt_numbers'); }
};