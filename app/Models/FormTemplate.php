<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model {
    protected $fillable = [
        'department', 'level', 'subjects',
        'academic_year', 'is_active', 'created_by'
    ];

    protected $casts = ['subjects' => 'array'];
}