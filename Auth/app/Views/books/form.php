<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$book = $book ?? null;
$errors = $errors ?? [];
$isEdit = $book !== null;
?>

<div class="mb-4">
    <h1 class="h3 mb-1"><?= $isEdit ? 'Edit Buku' : 'Tambah Buku' ?></h1>
    <p class="text-muted mb-0">Lengkapi informasi buku perpustakaan.</p>
</div>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Periksa kembali data berikut:</strong>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= $isEdit
            ? site_url('books/update/' . $book['id'])
            : site_url('books/create') ?>"
              method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= esc($category['id']) ?>"
                            <?= old('category_id', $book['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                            <?= esc($category['name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Kode Buku</label>
                <input type="text" id="code" name="code" class="form-control"
                       maxlength="30" value="<?= esc(old('code', $book['code'] ?? '')) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Judul Buku</label>
                <input type="text" id="title" name="title" class="form-control"
                       maxlength="200" value="<?= esc(old('title', $book['title'] ?? '')) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Penulis</label>
                <input type="text" id="author" name="author" class="form-control"
                       maxlength="150" value="<?= esc(old('author', $book['author'] ?? '')) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="publisher" class="form-label">Penerbit</label>
                <input type="text" id="publisher" name="publisher" class="form-control"
                       maxlength="150"
                       value="<?= esc(old('publisher', $book['publisher'] ?? '')) ?>">
            </div>

            <div class="mb-3">
                <label for="published_year" class="form-label">Tahun Terbit</label>
                <input type="number" id="published_year" name="published_year"
                       class="form-control" min="1000" max="<?= date('Y') ?>"
                       value="<?= esc(old('published_year', $book['published_year'] ?? '')) ?>">
            </div>

            <div class="mb-3">
                <label for="stock_total" class="form-label">Stok Total</label>
                <input type="number" id="stock_total" name="stock_total"
                       class="form-control" min="0"
                       value="<?= esc(old('stock_total', $book['stock_total'] ?? 0)) ?>"
                       required>
                <div class="form-text">Masukkan jumlah seluruh eksemplar buku.</div>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" name="is_active" value="1"
                       class="form-check-input" id="is_active"
                       <?= old('is_active', $book['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Buku aktif</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Update Buku' : 'Simpan Buku' ?>
            </button>
            <a href="<?= site_url('books') ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
