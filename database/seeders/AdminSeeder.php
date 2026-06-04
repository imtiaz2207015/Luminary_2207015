<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Luminary Admin',
            'email' => 'admin@luminary.app',
            'password' => Hash::make('admin123456'),
            'role' => 'admin',
        ]);
        echo "✅ Admin created! Email: admin@luminary.app | Password: admin123456\n";
    }
}