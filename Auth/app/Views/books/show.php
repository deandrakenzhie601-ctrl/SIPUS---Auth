<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Detail Buku</h1>
        <p class="text-muted mb-0"><?= esc($book['title']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('books/edit/' . $book['id']) ?>" class="btn btn-warning">Edit</a>
        <a href="<?= site_url('books') ?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h5 mb-3">Informasi Buku</h2>
        <dl class="row mb-0">
            <dt class="col-sm-3">Kode Buku</dt>
            <dd class="col-sm-9"><?= esc($book['code']) ?></dd>

            <dt class="col-sm-3">Judul</dt>
            <dd class="col-sm-9"><?= esc($book['title']) ?></dd>

            <dt class="col-sm-3">Kategori</dt>
            <dd class="col-sm-9">
                <?= esc($book['category_name']) ?>
                <?php if (! empty($book['category_description'])): ?>
                    <span class="text-muted">- <?= esc($book['category_description']) ?></span>
                <?php endif ?>
            </dd>

            <dt class="col-sm-3">Penulis</dt>
            <dd class="col-sm-9"><?= esc($book['author']) ?></dd>

            <dt class="col-sm-3">Penerbit</dt>
            <dd class="col-sm-9"><?= esc($book['publisher'] ?: '-') ?></dd>

            <dt class="col-sm-3">Tahun Terbit</dt>
            <dd class="col-sm-9"><?= esc($book['published_year'] ?: '-') ?></dd>

            <dt class="col-sm-3">Stok</dt>
            <dd class="col-sm-9"><?= esc($book['stock_available']) ?> tersedia dari <?= esc($book['stock_total']) ?></dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">
                <?php if ($book['is_active']): ?>
                    <span class="badge bg-success">Aktif</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Nonaktif</span>
                <?php endif ?>
            </dd>
        </dl>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 mb-3">Riwayat Peminjaman</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Jumlah</th>
                        <th>Dikembalikan</th>
                        <th>Belum Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($loanHistory)): ?>
                        <?php foreach ($loanHistory as $loan): ?>
                            <tr>
                                <td><?= esc($loan['loan_code']) ?></td>
                                <td><?= esc($loan['member_name']) ?> (<?= esc($loan['member_code']) ?>)</td>
                                <td><?= esc($loan['loan_date']) ?></td>
                                <td><?= esc($loan['due_date']) ?></td>
                                <td><?= esc($loan['quantity']) ?></td>
                                <td><?= esc($loan['returned_quantity']) ?></td>
                                <td><?= esc($loan['remaining_quantity']) ?></td>
                                <td><?= esc(ucfirst($loan['loan_status'])) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada riwayat peminjaman untuk buku ini.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
