<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPemasukanModal">
    <i class="bi bi-plus-circle"></i> Tambah Pemasukan
</button>

<div class="modal fade" id="tambahPemasukanModal" tabindex="-1" aria-labelledby="tambahPemasukanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPemasukanModalLabel">Form Tambah Pemasukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form action="<?= BASEURL ?>/transaksi/tambah_transaksi/pemasukan" method="POST">
                    <div class="mb-3">
                        <label for="jumlahPemasukan" class="form-label">Jumlah</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="jumlahPemasukan" name="jumlah"
                                placeholder="Contoh: 1500000" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsiPemasukan" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsiPemasukan" name="deskripsi"
                            placeholder="Contoh: Gaji bulan Oktober" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggalPemasukan" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggalPemasukan" name="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategoriPemasukan" class="form-label">Kategori</p>
                            <select class="form-select" id="kategoriPemasukan" name="kategori_id" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option selected>Semua Kategori</option>
                                <?php foreach ($semua_kategori as $kategori): ?>
                                    <?php if ($kategori['jenis'] == 1): ?>
                                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Pemasukan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // Set nilai default input tanggal ke tanggal hari ini
    document.getElementById('tanggalPemasukan').valueAsDate = new Date();
</script>