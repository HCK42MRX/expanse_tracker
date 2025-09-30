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
        foreach ($required_fields as $fields) {
            if (!isset($data[$fields]) || $data[$fields] === '') {
                $errors[$fields] = "Field {$fields} harus diisi";
                continue;
            }
            $clean_data[$fields] = trim(htmlspecialchars($data[$fields]));

            switch ($fields) {
                case 'jumlah':
                    if (!filter_var($clean_data[$fields], FILTER_VALIDATE_INT)) {
                        $errors[$fields] = "Field {$fields} harus berupa angka";
                        continue 2;
                    }
                    $clean_data[$fields] = abs(filter_var($clean_data[$fields], FILTER_SANITIZE_NUMBER_INT));
                    break;

                case 'deskripsi':
                    $clean_data[$fields] = htmlspecialchars($clean_data[$fields], ENT_QUOTES, 'UTF-8');
                    break;

                case 'tanggal':
                    // Validasi apakah formatnya YYYY-MM-DD
                    $d = DateTime::createFromFormat('Y-m-d', $clean_data[$fields]);
                    if (!$d || $d->format('Y-m-d') !== $clean_data[$fields]) {
                        $errors[$fields] = "Format tanggal harus YYYY-MM-DD";
                        continue 2;
                    }
                    // Jika valid, datanya sudah aman dan tidak perlu sanitasi string lagi
                    break;
                case 'kategori_id':
                    if (!filter_var($clean_data[$fields], FILTER_VALIDATE_INT) || !in_array($clean_data[$fields], $this->validCategoryId)) {
                        $errors[$fields] = "Field {$fields} tidak valid";
                        continue 2;
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
        }
    }
}