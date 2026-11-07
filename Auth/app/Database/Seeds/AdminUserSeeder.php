<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'          => 'Administrator',
                'email'         => 'admin@sipus.test',
                'password_hash' => password_hash(
                    'admin123',
                    PASSWORD_DEFAULT
                ),
                'role'          => 'admin',
                'is_active'     => 1,
            ],
            [
                'name'          => 'Petugas Perpustakaan',
                'email'         => 'petugas@sipus.test',
                'password_hash' => password_hash(
                    'petugas123',
                    PASSWORD_DEFAULT
                ),
                'role'          => 'petugas',
                'is_active'     => 1,
            ],
        ];

        foreach ($users as $user) {
            $exists = $this->db->table('users')
                ->where('email', $user['email'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('users')->insert($user);
            }
        }
    }
}