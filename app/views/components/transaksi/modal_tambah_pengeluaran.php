<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tambahPengeluaranModal">
    <i class="bi bi-dash-circle"></i> Tambah Pengeluaran
</button>

<div class="modal fade" id="tambahPengeluaranModal" tabindex="-1" aria-labelledby="tambahPengeluaranModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPengeluaranModalLabel">Form Tambah Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form action="<?= BASEURL ?>/transaksi/tambah_transaksi/pengeluaran" method="POST">
                    <div class="mb-3">
                        <label for="jumlahPengeluaran" class="form-label">Jumlah</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="jumlahPengeluaran" name="jumlah"
                                placeholder="Contoh: 50000" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsiPengeluaran" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsiPengeluaran" name="deskripsi"
                            placeholder="Contoh: Bayar tagihan listrik" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggalPengeluaran" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggalPengeluaran" name="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategoriPengeluaran" class="form-label">Kategori</p>
                            <select class="form-select" id="kategoriPengeluaran" name="kategori_id" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option selected>Semua Kategori</option>
                                <?php foreach ($semua_kategori as $kategori): ?>
                                    <?php if ($kategori['jenis'] == 0): ?>
                                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Set nilai default input tanggal ke tanggal hari ini
    document.getElementById('tanggalPengeluaran').valueAsDate = new Date();
</script>