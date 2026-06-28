<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['student', 'doctor', 'shuoun'])->default('student');
            $table->string('academic_id')->nullable()->unique(); 
            $table->string('national_id')->nullable();          
            $table->string('department')->nullable();           
            $table->string('level')->nullable();                 
            $table->string('academic_advisor_name')->nullable(); 
            
            $table->text('signature_data')->nullable();          
            $table->string('stamp_path')->nullable();           
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};