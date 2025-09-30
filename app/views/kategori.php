<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Manajemen Kategori</h1>
</div>

<div class="container mt-4">
    <?php
    // Panggil method flash() di sini!
    Flasher::flash();
    ?>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Kategori Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="<?= BASEURL ?>/kategori/tambah" method="POST">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                            placeholder="Contoh: Makanan" required>
                    </div>
                    <div class="mb-3">
                        <label for="Jenis" class="form-label">Jenis Kategori</label>
                        <select class="form-select" id="Jenis" name="jenis" required>
                            <option value="0" selected>Pengeluaran</option>
                            <option value="1">Pemasukan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul"></i> Daftar Kategori
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Jenis</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kategori as $index => $k): ?>
                                <tr>
                                    <th scope="row"><?= $index + 1 ?></th>
                                    <td><?= $k['nama_kategori'] ?></td>
                                    <?php if ($k['jenis'] == 0): ?>
                                        <td><span class="badge bg-danger">Pengeluaran</span></td>
                                    <?php else: ?>
                                        <td><span class="badge bg-success">Pemasukan</span></td>
                                    <?php endif; ?>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-warning" title="Edit"><i
                                                class="bi bi-pencil-square"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger" title="Hapus"><i
                                                class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASEURL ?>/js/kategori/pagination.js"></script>