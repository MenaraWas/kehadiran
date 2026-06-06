<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed akun admin default.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin E-Apel',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Akun admin berhasil dibuat/diperbarui!');
        $this->command->info('   Email    : admin@admin.com');
        $this->command->info('   Password : password');
    }
}
