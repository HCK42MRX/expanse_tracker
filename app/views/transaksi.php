<?php
// Ambil nilai filter dari POST, beri nilai default '' jika tidak ada
$filterStartDate = $_POST['startDate'] ?? '';
$filterEndDate = $_POST['endDate'] ?? '';
$filterCategory = $_POST['categoryFilter'] ?? '';
$filterType = $_POST['typeFilter'] ?? '';
?>

<style>
    /* Gaya untuk panah yang tidak aktif (default) */
    .sort-icon {
        margin-left: 5px;
        color: #a7a7a7;
        /* Warna abu-abu redup */
        transition: color 0.2s;
    }

    /* Gaya untuk panah yang aktif (kolom yang sedang di-sort) */
    .sort-icon.active {
        color: #ffffff;
        /* Warna putih cerah agar menonjol */
    }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <h1 class="h2 mb-0">Riwayat Transaksi</h1>
    <div class="d-flex gap-2">
        <a href="#" class="btn btn-outline-secondary">
            <i class="bi bi-download"></i>
            <span class="d-none d-sm-inline">Ekspor CSV</span> </a>
        <?php include 'components/transaksi/modal_tambah_pemasukan.php'; ?>
        <?php include 'components/transaksi/modal_tambah_pengeluaran.php'; ?>
    </div>
</div>

<div class="container mt-4">
    <?php Flasher::flash(); ?>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Filter Transaksi</h5>
        <form class="row g-3 align-items-end filter-form" method="POST" action="<?= BASEURL ?>/transaksi">

            <div class="col-12 col-sm-6 col-lg-5">
                <label for="startDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="startDate" name="startDate"
                    value="<?= htmlspecialchars($filterStartDate) ?>">
            </div>

            <div class="col-12 col-sm-6 col-lg-5">
                <label for="endDate" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="endDate" name="endDate"
                    value="<?= htmlspecialchars($filterEndDate) ?>">
            </div>

            <div class="col-12 col-lg-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100" title="Terapkan Filter">
                        <i class="bi bi-funnel-fill"></i>
                        <span class="d-none d-lg-inline"> Terapkan</span>
                    </button>
                    <a href="<?= BASEURL ?>/transaksi" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="row gy-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Total Pemasukan</h6>
                <p class="card-text fs-5 fw-bold">
                    + Rp <?= number_format($data['totals']['total_pemasukan'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Total Pengeluaran</h6>
                <p class="card-text fs-5 fw-bold">
                    - Rp <?= number_format(abs($data['totals']['total_pengeluaran']), 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Saldo Akhir</h6>
                <p class="card-text fs-5 fw-bold">
                    Rp <?= number_format($data['totals']['total_saldo'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <div class="col-12 col-md-4 col-lg-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" id="searchInput" class="form-control" placeholder="Cari transaksi...">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
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
                            <td>
                                <?php if ($t['jenis_kategori'] == 0): ?>
                                    <span class="badge bg-danger">Pengeluaran</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Pemasukan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($t['jumlah'][0] == "-"): ?>
                                    <span class="text-end text-danger fw-bold d-block">- Rp
                                        <?= number_format(abs($t['jumlah']), 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-end text-success fw-bold d-block">+ Rp
                                        <?= number_format($t['jumlah'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </td>
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