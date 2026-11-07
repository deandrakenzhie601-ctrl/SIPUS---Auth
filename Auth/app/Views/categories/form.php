<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Kategori - SIPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1 class="h3 mb-3">
        <?= $category !== null ? 'Edit Kategori' : 'Tambah Kategori' ?>
    </h1>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php
        $isEdit = $category !== null;
        $action = $isEdit
            ? site_url('categories/update/' . $category['id'])
            : site_url('categories/save');
    ?>
    <form action="<?= $action ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="name" class="form-label">Nama Kategori</label>
            <input type="text" id="name" name="name"
                   class="form-control"
                   value="<?= esc(old('name', $category['name'] ?? '')) ?>"
                   required maxlength="100">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description"
                      class="form-control" rows="3"
                      maxlength="500"><?= esc(old('description', $category['description'] ?? '')) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('categories') ?>"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>