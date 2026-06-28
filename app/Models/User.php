<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'academic_id', 'national_id', 'department',
        'level', 'academic_advisor_name',
        'signature_data', 'stamp_path'
    ];

    protected $hidden = ['password', 'remember_token', 'signature_data'];

    protected $casts = ['password' => 'hashed'];

    public function registrationForms() {
        return $this->hasMany(RegistrationForm::class, 'student_id');
    }

    public function assignments() {
        return $this->hasMany(AdvisorAssignment::class, 'doctor_id');
    }

    public function isStudent() { return $this->role === 'student'; }
    public function isDoctor()  { return $this->role === 'doctor';  }
    public function isShuoun()  { return $this->role === 'shuoun';  }
}