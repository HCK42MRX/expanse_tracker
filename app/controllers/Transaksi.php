<?php

class Transaksi extends Controller
{
    public function index()
    {
        $transaksiModel = $this->model('transaksi_model');
        $kategoriModel = $this->model('kategori_model');


        // Mengambil filter dari query string (misal: ?startDate=...)
        $filters = [
            'tanggal_mulai' => $_POST['startDate'] ?? null,
            'tanggal_akhir' => $_POST['endDate'] ?? null,
            'kategori_id' => $_POST['categoryFilter'] ?? null,
            'jenis' => $_POST['typeFilter'] ?? null,
        ];


        $filters = array_filter($filters, fn($value) => $value !== '' && $value !== null);

        $data['transaksi'] = $transaksiModel->getFilteredTransaksi($filters);
        $data['totals'] = $transaksiModel->getTransactionTotals($filters);
        $data['semua_kategori'] = $kategoriModel->getAllKategori();

        $this->view('layouts/header', $data);
        $this->view('transaksi', $data);
        $this->view('layouts/footer');
    }

    /**
     * PENYESUAIAN DI SINI:
     * Method ini sekarang menerima $parameter langsung dari router Anda.
     * @param string|null $parameter Berisi 'pemasukan' atau 'pengeluaran' dari URL.
     */
    public function tambah_transaksi($parameter = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
            header('location: ' . BASEURL . '/transaksi');
            exit;
        }

        $transaksiModel = $this->model('transaksi_model');
        $kategoriModel = $this->model('kategori_model');
        $validator = new Validator();

        $validCategoryIds = $kategoriModel->getIdKategori();
        $rules = [
            'jumlah' => ['required', 'numeric', 'not_zero'],
            'deskripsi' => ['required', 'text'],
            'tanggal' => ['required', 'date'],
            'kategori_id' => ['required', 'in:' . implode(',', $validCategoryIds)],
        ];

        // Kita tidak perlu lagi mem-parsing $_GET['url'] di sini!
        // Cukup gunakan variabel $parameter yang sudah disediakan router.

        if ($validator->validate($_POST, $rules)) {
            $clean_data = $validator->getSanitizedData();
            $clean_data['tanggal'] = $clean_data['tanggal'] . ' ' . date('H:i:s');

            if ($parameter === 'pemasukan') {
                $clean_data['jumlah'] = abs($clean_data['jumlah']);
                if ($transaksiModel->postNewTransaksi($clean_data) > 0) {
                    Flasher::setFlash('Berhasil', 'menambahkan pemasukan.', 'success');
                }
            } elseif ($parameter === 'pengeluaran') {
                $totals = $transaksiModel->getTransactionTotals();
                $jumlahPengeluaran = abs($clean_data['jumlah']);

                if ($totals['total_saldo'] < $jumlahPengeluaran) {
                    Flasher::setFlash('Gagal', 'Saldo tidak mencukupi untuk pengeluaran ini.', 'danger');
                } else {
                    $clean_data['jumlah'] = -$jumlahPengeluaran;
                    if ($transaksiModel->postNewTransaksi($clean_data) > 0) {
                        Flasher::setFlash('Berhasil', 'menambahkan pengeluaran.', 'success');
                    }
                }
            } else {
                Flasher::setFlash('Gagal', 'Jenis transaksi tidak valid.', 'danger');
            }
        } else {
            $errors = $validator->getErrors();
            $error_html = '<ul>';
            foreach ($errors as $error) {
                $error_html .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $error_html .= '</ul>';
            Flasher::setFlash('Validasi Gagal', $error_html, 'danger');
        }

        header('location: ' . BASEURL . '/transaksi');
        exit;
    }
}