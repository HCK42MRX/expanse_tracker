<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Riwayat Transaksi</h1>
    <div>
        <a href="#" class="btn btn-outline-secondary">
            <i class="bi bi-download"></i> Ekspor CSV
        </a>
        <?php include 'components/transaksi/modal_tambah_pemasukan.php'; ?>

        <?php include 'components/transaksi/modal_tambah_pengeluaran.php'; ?>
    </div>
</div>

<div class="container mt-4">
    <?php
    // Panggil method flash() di sini!
    Flasher::flash();
    ?>
</div>


<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Filter Transaksi</h5>
        <form class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="startDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="startDate">
            </div>
            <div class="col-md-3">
                <label for="endDate" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="endDate">
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Kategori</label>
                <select class="form-select" id="<?= $transaksi['kategori_id'] ?>">
                    <option selected>Semua Kategori</option>
                    <?php foreach ($semua_kategori as $kategori): ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Total Pemasukan</h6>
                <p class="card-text fs-5 fw-bold">
                    + Rp <?= number_format($data['total_pemasukan']['total'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6 class="card-title">Total Pengeluaran</h6>
                <p class="card-text fs-5 fw-bold">
                    - Rp <?= number_format($data['total_pengeluaran']['total'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Saldo Akhir</h6>
                <p class="card-text fs-5 fw-bold">
                    Rp <?= number_format($data['total_saldo']['total'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" data-column="tanggal" style="cursor: pointer;">Tanggal</th>
                        <th scope="col" data-column="deskripsi" style="cursor: pointer;">Deskripsi</th>
                        <th scope="col" data-column="kategori" style="cursor: pointer;">Kategori</th>
                        <th scope="col" data-column="jenis" style="cursor: pointer;">Jenis</th>
                        <th scope="col" data-column="jumlah" class="text-end" style="cursor: pointer;">Jumlah</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transaction-table-body">
                    <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><?= $t['created_at'] ?></td>
                            <td><?= $t['deskripsi'] ?></td>
                            <td><?= $t['nama_kategori'] ?></td>
                            <?php if ($t['jenis_kategori'] == 0): ?>
                                <td><span class="badge bg-danger">Pengeluaran</span></td>
                            <?php else: ?>
                                <td><span class="badge bg-success">Pemasukan</span></td>
                            <?php endif; ?>
                            <?php if ($t['jumlah'][0] == "-"): ?>
                                <td class="text-end text-danger fw-bold">- Rp
                                    <?= number_format(abs($t['jumlah']), 0, ',', '.') ?>
                                </td>
                            <?php else: ?>
                                <td class="text-end text-success fw-bold">+ Rp
                                    <?= number_format($t['jumlah'], 0, ',', '.') ?>
                                </td>
                            <?php endif; ?>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-warning" title="Edit"><i
                                        class="bi bi-pencil-square"></i></a>
                                <a href="#" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="pagination-wrapper" class="d-flex justify-content-end mt-3"></div>

<script src="<?= BASEURL ?>/js/transaksi/pagination.js"></script>
<script src="<?= BASEURL ?>/js/transaksi/table.js"></script>