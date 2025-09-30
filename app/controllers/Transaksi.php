<?php

class Transaksi extends Controller
{
    private $validCategoryId;
    public function __construct()
    {
        $this->validCategoryId = $this->model('kategori_model')->getIdKategori();
    }
    private function advance_filter($data, $required_fields)
    {
        $errors = [];
        $clean_data = [];

        foreach ($required_fields as $field) {
            // 1. Cek apakah field ada dan tidak kosong
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[$field] = "Field {$field} harus diisi";
                continue; // Lanjut ke field berikutnya jika kosong, tidak perlu validasi format lagi
            }

            // Jika tidak kosong, bersihkan dan validasi formatnya
            $clean_data[$field] = trim($data[$field]);

            switch ($field) {
                case 'jumlah':
                    // Cek apakah numeric (bisa int, float, atau string angka)
                    if (!is_numeric($clean_data[$field])) {
                        $errors[$field] = "Field {$field} harus berupa angka";
                    } else {
                        // Jika sudah pasti angka, baru sanitasi
                        $clean_data[$field] = abs(filter_var($clean_data[$field], FILTER_SANITIZE_NUMBER_INT));
                    }
                    break;

                case 'deskripsi':
                    $clean_data[$field] = htmlspecialchars($clean_data[$field], ENT_QUOTES, 'UTF-8');
                    break;

                case 'tanggal':
                    $d = DateTime::createFromFormat('Y-m-d', $clean_data[$field]);
                    if (!$d || $d->format('Y-m-d') !== $clean_data[$field]) {
                        $errors[$field] = "Format tanggal harus YYYY-MM-DD";
                    }
                    break;

                case 'kategori_id':
                    // Cek apakah integer DAN ada di dalam daftar ID yang valid
                    if (!filter_var($clean_data[$field], FILTER_VALIDATE_INT) || !in_array($clean_data[$field], $this->validCategoryId)) {
                        $errors[$field] = "Field {$field} tidak valid";
                    }
                    break;
            }
        }

        return [
            'success' => empty($errors),
            'errors' => $errors,
            'data' => $clean_data
        ];
    }
    public function index()
    {
        $this->view('layouts/header');
        $data['transaksi'] = $this->model('transaksi_model')->getAllTransaksi();
        $data['semua_kategori'] = $this->model('kategori_model')->getAllKategori();
        $this->view('transaksi', $data);
        $this->view('layouts/footer');
    }

    public function tambah_transaksi()
    {
        $required_fields = ['jumlah', 'deskripsi', 'tanggal', 'kategori_id'];
        $validation = $this->advance_filter($_POST, $required_fields);
        $parameter = explode('/', $_GET['url'])[2];
        if ($validation['success']) {
            $clean_data = $validation['data'];

            if (isset($parameter) && $parameter === 'pemasukan') {
                if ($this->model('transaksi_model')->postNewTransaksi($clean_data) > 0) {
                    Flasher::setFlash('Berhasil', 'menambahkan pemasukan', 'success');
                    header('location: ' . BASEURL . '/transaksi');
                    exit;
                }
            } else if (isset($parameter) && $parameter === 'pengeluaran') {
                $clean_data['jumlah'] = -abs($clean_data['jumlah']);
                if ($this->model('transaksi_model')->postNewTransaksi($clean_data) > 0) {
                    Flasher::setFlash('Berhasil', 'menambahkan pengeluaran', 'success');
                    header('location: ' . BASEURL . '/transaksi');
                    exit;
                }
            } else {
                Flasher::setFlash('Data input tidak sesuai', 'input salah', 'danger');
                header('location: ' . BASEURL . '/transaksi');
                exit;
            }
        } else {
            // 1. Kumpulkan semua pesan error ke dalam sebuah array
            $error_messages = [];
            foreach ($validation['errors'] as $field => $message) {
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