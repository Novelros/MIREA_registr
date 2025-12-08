<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@mirea.ru',
            'password' => Hash::make('1111'), 
            'role' => 'admin',
            'phone' => '+79990001122'
        ]);

        // Создаем тестовых студентов
        User::create([
            'name' => 'Иван Иванов',
            'email' => 'student1@mirea.ru',
            'password' => Hash::make('student1'), 
            'role' => 'student',
            'phone' => '+79991234567'
        ]);

        User::create([
            'name' => 'Петр Петров',
            'email' => 'student2@mirea.ru',
            'password' => Hash::make('student2'), 
            'role' => 'student',
            'phone' => '+79998765432'
        ]);

        User::create([
            'name' => 'Мария Сидорова',
            'email' => 'student3@mirea.ru',
            'password' => Hash::make('student3'),
            'role' => 'student',
            'phone' => '+79997654321'
        ]);
    }
}