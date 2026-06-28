<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model {
    protected $fillable = [
        'student_id', 'national_id', 'academic_email',
        'department', 'level', 'receipt_number',
        'receipt_image_path', 'academic_advisor_name',
        'subjects', 'student_signature', 'status',
        'doctor_signature', 'doctor_stamp_path', 'doctor_signed_at',
        'approved_by', 'approved_at', 'rejection_reason',
        'pdf_path', 'unique_hash', 'qr_code_path'
    ];

    protected $casts = [
        'subjects' => 'array',
        'doctor_signed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending_doctor'  => 'في انتظار توقيع المرشد',
            'pending_shuoun'  => 'في انتظار موافقة الشؤون',
            'approved'        => 'تمت الموافقة',
            'rejected'        => 'مرفوض',
            default           => 'غير معروف'
        };
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'pending_doctor'  => 'warning',
            'pending_shuoun'  => 'info',
            'approved'        => 'success',
            'rejected'        => 'danger',
            default           => 'secondary'
        };
    }
}