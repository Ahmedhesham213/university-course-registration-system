<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    public function run(): void {
       
        User::create([
            'name'     => 'الشؤون الطلابية',
            'email'    => 'shuoun@mtis.edu.eg',
            'password' => Hash::make('Shuoun@2024'),
            'role'     => 'shuoun',
        ]);

        
        User::create([
            'name'     => 'د. أحمد محمد',
            'email'    => 'ahmed@mtis.edu.eg',
            'password' => Hash::make('Doctor@2024'),
            'role'     => 'doctor',
        ]);

        
        User::create([
            'name'        => 'محمد علي',
            'email'       => 'student@mtis.edu.eg',
            'password'    => Hash::make('Student@2024'),
            'role'        => 'student',
            'national_id' => '30012345678901',
            'academic_id' => '2021001',
            'department'  => 'information_systems',
            'level'       => '3',
        ]);
    }
}