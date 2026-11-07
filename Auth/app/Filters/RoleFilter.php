<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        $userRole = session()->get('user_role');

        if (
            empty($arguments) ||
            ! in_array($userRole, $arguments, true)
        ) {
            return redirect()->to('/dashboard')
                ->with('error', 'Anda tidak memiliki hak akses.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada proses setelah request.
    }
}