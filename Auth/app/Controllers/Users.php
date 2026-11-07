<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        $users = $model->orderBy('id', 'ASC')->findAll();

        return view('users/index', [
            'title' => 'Data Pengguna',
            'users' => $users,
        ]);
    }
}
