<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registration_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('national_id');
            $table->string('academic_email');
            $table->string('department');
            $table->string('level');
            $table->string('receipt_number')->unique(); 
            $table->string('receipt_image_path');        
            $table->string('academic_advisor_name');
            $table->json('subjects');                    
            $table->text('student_signature');           
            $table->enum('status', [
                'pending_doctor',   
                'pending_shuoun',    
                'approved',          
                'rejected'           
            ])->default('pending_doctor');
            
            $table->text('doctor_signature')->nullable();
            $table->string('doctor_stamp_path')->nullable();
            $table->timestamp('doctor_signed_at')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->string('pdf_path')->nullable();
            $table->string('unique_hash')->nullable();   
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('registration_forms'); }
};