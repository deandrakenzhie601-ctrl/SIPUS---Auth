<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $totalBooks = $db->table('books')->countAllResults();
        $totalCategories = $db->table('categories')->countAllResults();
        $totalMembers = $db->table('members')->countAllResults();
        $totalLoans = $db->table('loans')->countAllResults();

        return view('dashboard/index', [
            'totalBooks'      => $totalBooks,
            'totalCategories' => $totalCategories,
            'totalMembers'    => $totalMembers,
            'totalLoans'      => $totalLoans,
        ]);
    }
}
