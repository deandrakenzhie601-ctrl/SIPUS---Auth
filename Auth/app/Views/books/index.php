<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Data Buku</h1>
        <p class="text-muted mb-0">Kelola koleksi buku perpustakaan.</p>
    </div>

    <a href="<?= site_url('books/new') ?>" class="btn btn-primary">
        + Tambah Buku
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Jumlah seluruh judul buku</div>
                <div class="fs-3 fw-semibold"><?= esc($totalBooks) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted">Eksemplar tersedia</div>
                <div class="fs-3 fw-semibold"><?= esc($availableCopies) ?></div>
            </div>
        </div>
    </div>
</div>

<?php if ($message = session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif ?>

<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc($message) ?>
    </div>
<?php endif ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="<?= site_url('books') ?>" method="get" class="row g-2 align-items-end mb-4">
            <div class="col-md-4">
                <label for="q" class="form-label">Cari judul buku</label>
                <input type="search" id="q" name="q" class="form-control"
                       value="<?= esc($search) ?>" placeholder="Masukkan judul buku">
            </div>
            <div class="col-md-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Semua kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= esc($category['id']) ?>"
                            <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>>
                            <?= esc($category['name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-check mb-2">
                    <input type="checkbox" id="active" name="active" value="1"
                           class="form-check-input" <?= $activeOnly ? 'checked' : '' ?>>
                    <label class="form-check-label" for="active">Buku aktif saja</label>
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Cari</button>
                <a href="<?= site_url('books') ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <p class="text-muted">Menampilkan <?= esc(count($books)) ?> judul dari <?= esc($totalBooks) ?> buku.</p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($books)): ?>
                        <?php foreach ($books as $index => $book): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($book['code']) ?></td>
                                <td><?= esc($book['title']) ?></td>
                                <td><?= esc($book['category_name']) ?></td>
                                <td><?= esc($book['author']) ?></td>
                                <td>
                                    <?= esc($book['stock_available']) ?>
                                    /
                                    <?= esc($book['stock_total']) ?>
                                </td>
                                <td>
                                    <?php if ($book['is_active']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif ?>
                                </td>
                                <td class="text-nowrap">
                                    <a href="<?= site_url('books/' . $book['id']) ?>"
                                       class="btn btn-sm btn-info">
                                        Detail
                                    </a>
                                    <a href="<?= site_url('books/edit/' . $book['id']) ?>"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="<?= site_url('books/delete/' . $book['id']) ?>"
                                          method="post"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                Tidak ada buku yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>