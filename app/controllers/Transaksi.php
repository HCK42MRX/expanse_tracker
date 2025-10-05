<?php

class Transaksi extends Controller
{

    public function index()
    {
        $this->view('layouts/header');
        $data['total_saldo'] = $this->model('transaksi_model')->getSumTransaksi();
        $data['total_pemasukan'] = $this->model('transaksi_model')->getSumTransaksi(null, null, "pemasukan");
        $data['total_pengeluaran'] = $this->model('transaksi_model')->getSumTransaksi(null, null, "pengeluaran");
        $data['transaksi'] = $this->model('transaksi_model')->getAllTransaksi();
        $data['semua_kategori'] = $this->model('kategori_model')->getAllKategori();
        $this->view('transaksi', $data);
        $this->view('layouts/footer');
    }

    public function tambah_transaksi()
    {
        $validator = new Validator();
        $validCategoryIds = $this->model('kategori_model')->getIdKategori();

        // 3. Tentukan aturan validasi
        $rules = [
            'jumlah' => ['required', 'numeric'],
            'deskripsi' => ['required', 'text'],
            'tanggal' => ['required', 'date'],
            'kategori_id' => ['required', 'in:' . implode(',', $validCategoryIds)] // 'in:34,35,36...'
        ];
        $parameter = explode('/', $_GET['url'])[2];
        if ($validator->validate($_POST, $rules)) {
            $clean_data = $validator->getSanitizedData();
            $clean_data['tanggal'] = $clean_data['tanggal'] . ' ' . date('H:i:s');

            if (isset($parameter) && $parameter === 'pemasukan') {
                $clean_data['jumlah'] = abs($clean_data['jumlah']);
                if ($this->model('transaksi_model')->postNewTransaksi($clean_data) > 0) {
                    Flasher::setFlash('Berhasil', 'menambahkan pemasukan', 'success');
                    header('location: ' . BASEURL . '/transaksi');
                    exit;
                }
            } else if (isset($parameter) && $parameter === 'pengeluaran') {
                $clean_data['jumlah'] = -abs($clean_data['jumlah']);
                if ((int) $this->model('transaksi_model')->getSumTransaksi(null, null, "pemasukan")['total'] < abs($clean_data['jumlah'])) {
                    Flasher::setFlash('Gagal', 'pengeluaran lebih banyak dari pemasukan', 'danger');
                    header('location: ' . BASEURL . '/transaksi');
                    exit;
                } else {

                    if ($this->model('transaksi_model')->postNewTransaksi($clean_data) > 0) {
                        Flasher::setFlash('Berhasil', 'menambahkan pengeluaran', 'success');
                        header('location: ' . BASEURL . '/transaksi');
                        exit;
                    }
                }
            } else {
                Flasher::setFlash('Data input tidak sesuai', 'input salah', 'danger');
                header('location: ' . BASEURL . '/transaksi');
                exit;
            }
        } else {
            // 1. Kumpulkan semua pesan error ke dalam sebuah array
            $error_messages = [];
            foreach ($validator->getErrors() as $field => $message) {
                $error_messages[] = $message;
            }

            // 2. Gabungkan pesan-pesan tersebut menjadi satu string HTML (daftar)
            $error_html = 'Terdapat beberapa kesalahan:<ul>';
            foreach ($error_messages as $error) {
                // Gunakan htmlspecialchars untuk keamanan jika pesan mengandung karakter khusus
                $error_html .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $error_html .= '</ul>';

            // 3. Panggil setFlash HANYA SEKALI dengan semua pesan yang sudah digabung
            Flasher::setFlash('Gagal', $error_html, 'danger');

            header('location: ' . BASEURL . '/transaksi');
            exit;
        }
    }
}