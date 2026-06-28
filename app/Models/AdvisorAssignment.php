<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisorAssignment extends Model {
    protected $fillable = ['doctor_id', 'student_email'];

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}