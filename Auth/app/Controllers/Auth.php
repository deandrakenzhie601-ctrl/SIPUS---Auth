<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return $this->login();
    }

    public function login()
    {
        // Jika sudah login, langsung ke dashboard.
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'errors' => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        $model = new UserModel();

        $user = $model->where('email', $email)
            ->first();

        // Periksa apakah akun ditemukan dan aktif.
        if (! $user || (int) $user['is_active'] !== 1) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Verifikasi password yang tersimpan di database.
        if (! password_verify($password, $user['password_hash'] ?? '')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Buat ulang session ID setelah login.
        session()->regenerate();

        // Simpan informasi pengguna ke session.
        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_email'=> $user['email'],
            'user_role' => $user['role'],
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard')
            ->with('success', 'Selamat datang di SIPUS.');
    }

    public function logout()
    {
        // Hapus seluruh data session.
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda berhasil logout.');
    }
}       