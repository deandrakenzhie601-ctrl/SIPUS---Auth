<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\CategoryModel;

class Books extends BaseController
{
    public function index()
    {
        $model = new BookModel();
        $search = trim((string) $this->request->getGet('q'));
        $categoryParam = (string) $this->request->getGet('category_id');
        $categoryId = ctype_digit($categoryParam) && (int) $categoryParam > 0
            ? (int) $categoryParam
            : null;
        $activeOnly = $this->request->getGet('active') === '1';
        $db = db_connect();
        $availableCopies = $db->table('books')
            ->selectSum('stock_available', 'total')
            ->get()
            ->getRowArray();

        return view('books/index', [
            'books'           => $model->getBooksWithCategory($search, $categoryId, $activeOnly),
            'categories'      => (new CategoryModel())->orderBy('name', 'ASC')->findAll(),
            'search'          => $search,
            'categoryId'      => $categoryId,
            'activeOnly'      => $activeOnly,
            'totalBooks'      => $model->countAll(),
            'availableCopies' => (int) ($availableCopies['total'] ?? 0),
        ]);
    }

    public function show($id)
    {
        $model = new BookModel();
        $book = $model->getBookWithCategory((int) $id);

        if (! $book) {
            return redirect()->to('/books')
                ->with('error', 'Buku tidak ditemukan.');
        }

        $loanHistory = db_connect()->query(
            'SELECT loans.loan_code, members.member_code, members.name AS member_name,
                    loans.loan_date, loans.due_date, loans.status AS loan_status,
                    loan_details.quantity,
                    COALESCE(returns.returned_quantity, 0) AS returned_quantity,
                    loan_details.quantity - COALESCE(returns.returned_quantity, 0) AS remaining_quantity
             FROM loan_details
             JOIN loans ON loans.id = loan_details.loan_id
             JOIN members ON members.id = loans.member_id
             LEFT JOIN (
                 SELECT loan_detail_id, SUM(quantity) AS returned_quantity
                 FROM loan_returns
                 GROUP BY loan_detail_id
             ) AS returns ON returns.loan_detail_id = loan_details.id
             WHERE loan_details.book_id = ?
             ORDER BY loans.loan_date DESC, loan_details.id DESC',
            [(int) $id]
        )->getResultArray();

        return view('books/show', [
            'book'        => $book,
            'loanHistory' => $loanHistory,
        ]);
    }

    public function new()
    {
        $categoryModel = new CategoryModel();

        return view('books/form', [
            'book'       => null,
            'categories' => $categoryModel->orderBy('name', 'ASC')->findAll(),
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function create()
    {
        $rules = [
            'category_id' => 'required|is_not_unique[categories.id]',
            'code'        => 'required|max_length[30]|is_unique[books.code]',
            'title'       => 'required|min_length[2]|max_length[200]',
            'author'      => 'required|max_length[150]',
            'publisher'   => 'permit_empty|max_length[150]',
            'published_year' => 'permit_empty|integer|greater_than_equal_to[1000]|less_than_equal_to[' . date('Y') . ']',
            'stock_total' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $stockTotal = (int) $this->request->getPost('stock_total');

        $model = new BookModel();

        $model->insert([
            'category_id'    => $this->request->getPost('category_id'),
            'code'           => trim($this->request->getPost('code')),
            'title'          => trim($this->request->getPost('title')),
            'author'         => trim($this->request->getPost('author')),
            'publisher'      => $this->request->getPost('publisher') ?: null,
            'published_year' => $this->request->getPost('published_year') ?: null,
            'stock_total'    => $stockTotal,
            'stock_available'=> $stockTotal,
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/books')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new BookModel();
        $book = $model->find($id);

        if (! $book) {
            return redirect()->to('/books')
                ->with('error', 'Buku tidak ditemukan.');
        }

        $categoryModel = new CategoryModel();

        return view('books/form', [
            'book'       => $book,
            'categories' => $categoryModel->orderBy('name', 'ASC')->findAll(),
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    public function update($id)
    {
        $model = new BookModel();
        $book = $model->find($id);

        if (! $book) {
            return redirect()->to('/books')
                ->with('error', 'Buku tidak ditemukan.');
        }

        $rules = [
            'category_id' => 'required|is_not_unique[categories.id]',
            'code'        => "required|max_length[30]|is_unique[books.code,id,{$id}]",
            'title'       => 'required|min_length[2]|max_length[200]',
            'author'      => 'required|max_length[150]',
            'publisher'   => 'permit_empty|max_length[150]',
            'published_year' => 'permit_empty|integer|greater_than_equal_to[1000]|less_than_equal_to[' . date('Y') . ']',
            'stock_total' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $newStockTotal = (int) $this->request->getPost('stock_total');

        // Hitung jumlah buku yang sedang dipinjam.
        $borrowed = (int) $book['stock_total']
                  - (int) $book['stock_available'];

        if ($newStockTotal < $borrowed) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'stock_total' =>
                        'Stok total tidak boleh lebih kecil daripada jumlah buku yang sedang dipinjam.',
                ]);
        }

        $model->update($id, [
            'category_id'     => $this->request->getPost('category_id'),
            'code'            => trim($this->request->getPost('code')),
            'title'           => trim($this->request->getPost('title')),
            'author'          => trim($this->request->getPost('author')),
            'publisher'       => $this->request->getPost('publisher') ?: null,
            'published_year'  => $this->request->getPost('published_year') ?: null,
            'stock_total'     => $newStockTotal,
            'stock_available' => $newStockTotal - $borrowed,
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/books')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new BookModel();
        $book = $model->find($id);

        if (! $book) {
            return redirect()->to('/books')
                ->with('error', 'Buku tidak ditemukan.');
        }

        // Pertahankan buku yang sudah tercatat dalam riwayat transaksi.
        $used = db_connect()->table('loan_details')
            ->where('book_id', $id)
            ->countAllResults();

        if ($used > 0) {
            return redirect()->to('/books')
                ->with('error', 'Buku sudah memiliki riwayat transaksi dan tidak dapat dihapus.');
        }

        $model->delete($id);

        return redirect()->to('/books')
            ->with('success', 'Buku berhasil dihapus.');
    }
}