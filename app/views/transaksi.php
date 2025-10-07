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
        <form class="row g-3 align-items-end filter-form">
            <div class="col-12 col-sm-6 col-lg-3">
                <label for="startDate" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="startDate">
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <label for="endDate" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="endDate">
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label for="categoryFilter" class="form-label">Kategori</label>
                <select class="form-select" id="categoryFilter">
                    <option selected>Semua</option>
                    <?php foreach ($semua_kategori as $kategori): ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <label for="typeFilter" class="form-label">Jenis</label>
                <select class="form-select" id="typeFilter">
                    <option selected>Semua</option>
                    <option value="1">Pemasukan</option>
                    <option value="0">Pengeluaran</option>
                </select>
            </div>
            <div class="col-12 col-lg-auto">
                <button type="submit" class="btn btn-primary w-100"> <i class="bi bi-funnel-fill"></i> Terapkan
                </button>
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

<script>
    // Tunggu sampai seluruh halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Cari form filter di dalam halaman
        const filterForm = document.querySelector('.filter-form');

        // Tambahkan event listener untuk event 'submit'
        filterForm.addEventListener('submit', function (event) {
            // 1. Mencegah form mengirim data (mencegah reload halaman)
            event.preventDefault();
            console.log(filterForm);

            // 2. Ambil nilai dari setiap input
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const category = document.getElementById('categoryFilter').value;
            const type = document.getElementById('typeFilter').value;

            // 3. Siapkan placeholder jika input kosong atau 'Semua'
            // Ini penting agar struktur URL tidak rusak
            const startDateParam = startDate || 'null';
            const endDateParam = endDate || 'null';
            const categoryParam = (category === 'Semua' || category === '') ? 'null' : category;
            const typeParam = (type === 'Semua' || type === '') ? 'null' : type;

            // 4. Bangun URL baru sesuai format yang diinginkan
            // Ganti BASEURL dengan variabel PHP Anda
            const baseUrl = "<?= BASEURL ?>";
            const newUrl = `${baseUrl}/transaksi/index/${startDateParam}/${endDateParam}/${categoryParam}/${typeParam}`;

            // 5. Arahkan browser ke URL yang baru
            window.location.href = newUrl;
        });
    });
</script>
<script src="<?= BASEURL ?>/js/transaksi/pagination.js"></script>
<script src="<?= BASEURL ?>/js/transaksi/table.js"></script>\