<?php

class Transaksi extends Controller
{
    public function index()
    {
        $transaksiModel = $this->model('transaksi_model');
        $kategoriModel = $this->model('kategori_model');

        // 1. Menggunakan $_POST untuk filter
        $filters = [
            'tanggal_mulai' => $_POST['startDate'] ?? null,
            'tanggal_akhir' => $_POST['endDate'] ?? null,
        ];

        // Membersihkan filter dari nilai kosong
        $filters = array_filter($filters, fn($value) => $value !== '' && $value !== null);

        $data['transaksi'] = $transaksiModel->getFilteredTransaksi($filters);
        $data['totals'] = $transaksiModel->getTransactionTotals($filters);
        // Karena filter kategori/jenis sudah dihapus dari view, ini mungkin tidak perlu lagi.
        // Jika masih diperlukan untuk modal, biarkan.
        $data['semua_kategori'] = $kategoriModel->getAllKategori();

        $this->view('layouts/header', $data);
        $this->view('transaksi', $data);
        $this->view('layouts/footer');
    }

    public function tambah_transaksi($parameter = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
            $this->redirectBack('transaksi');
        }

        $transaksiModel = $this->model('transaksi_model');
        $kategoriModel = $this->model('kategori_model');
        $validator = new Validator();

        $validCategoryIds = $kategoriModel->getIdKategori();
        $rules = [
            'jumlah' => ['required', 'numeric', 'not_zero'],
            'deskripsi' => ['required', 'text'],
            'tanggal' => ['required', 'date'],
            'kategori' => ['required', 'in:' . implode(',', $validCategoryIds)],
        ];

        if (!$validator->validate($_POST, $rules)) {
            $this->handleValidationErrors($validator->getErrors());
            $this->redirectBack('transaksi');
        }

        $clean_data = $validator->getSanitizedData();
        // Pertimbangkan: Apakah selalu ingin menambahkan waktu saat ini?
        // Mungkin lebih baik membiarkan user memilih waktu atau hanya menyimpan tanggal.
        $clean_data['tanggal'] = $clean_data['tanggal'] . ' ' . date('H:i:s');

        if ($parameter === 'pemasukan') {
            $this->handlePemasukan($transaksiModel, $clean_data);
        } elseif ($parameter === 'pengeluaran') {
            $this->handlePengeluaran($transaksiModel, $clean_data);
        } else {
            Flasher::setFlash('Gagal', 'Jenis transaksi tidak valid.', 'danger');
        }

        $this->redirectBack('transaksi');
    }

    /**
     * Menangani logika penambahan pemasukan.
     */
    private function handlePemasukan($transaksiModel, $data)
    {
        $data['jumlah'] = abs($data['jumlah']);
        if ($transaksiModel->postNewTransaksi($data) > 0) {
            Flasher::setFlash('Berhasil', 'menambahkan pemasukan.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'menambahkan pemasukan.', 'danger');
        }
    }

    /**
     * Menangani logika penambahan pengeluaran, termasuk pengecekan saldo.
     */
    private function handlePengeluaran($transaksiModel, $data)
    {
        $totals = $transaksiModel->getTransactionTotals();
        $jumlahPengeluaran = abs($data['jumlah']);

        if ($totals['total_saldo'] < $jumlahPengeluaran) {
            Flasher::setFlash('Gagal', 'Saldo tidak mencukupi untuk pengeluaran ini.', 'danger');
            return;
        }

        $data['jumlah'] = -$jumlahPengeluaran;
        if ($transaksiModel->postNewTransaksi($data) > 0) {
            Flasher::setFlash('Berhasil', 'menambahkan pengeluaran.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'menambahkan pengeluaran.', 'danger');
        }
    }
}