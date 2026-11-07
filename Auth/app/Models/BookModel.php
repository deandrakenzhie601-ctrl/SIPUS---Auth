<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table            = 'books';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'category_id',
        'code',
        'title',
        'author',
        'publisher',
        'published_year',
        'stock_total',
        'stock_available',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    /**
     * Mengambil daftar buku beserta nama kategorinya.
     */
    public function getBooksWithCategory(?string $search = null, ?int $categoryId = null, bool $activeOnly = false)
    {
        $builder = $this->select('books.*, categories.name AS category_name')
            ->join('categories', 'categories.id = books.category_id')
            ->orderBy('books.title', 'ASC');

        if ($search !== null && $search !== '') {
            $builder->like('books.title', $search);
        }

        if ($categoryId !== null) {
            $builder->where('books.category_id', $categoryId);
        }

        if ($activeOnly) {
            $builder->where('books.is_active', 1);
        }

        return $builder->findAll();
    }

    public function getBookWithCategory(int $id): ?array
    {
        return $this->select('books.*, categories.name AS category_name, categories.description AS category_description')
            ->join('categories', 'categories.id = books.category_id')
            ->where('books.id', $id)
            ->first();
    }
}