<?php

class Kategori extends Controller
{
    public function index()
    {
        $this->view('layouts/header');
        $data['kategori'] = $this->model('Kategori_model')->getAllKategori();
        $this->view('kategori', $data);
        $this->view('layouts/footer');
    }

    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
            header('location: ' . BASEURL . '/kategori');
            exit;
        }

        $kategoriModel = $this->model('kategori_model');
        $validator = new Validator();

        $rules = [
            'nama_kategori' => ['required', 'text'],
            'jenis' => ['required', 'in:1,0'],
        ];

        if (!$validator->validate($_POST, $rules)) {
            $this->handleValidationErrors($validator->getErrors());
            $this->redirectback('kategori');
        }

        $clean_data = $validator->getSanitizedData();

        if ($kategoriModel->postNewKategori($clean_data) > 0) {
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
            header('location: ' . BASEURL . '/kategori');
            $this->redirectback('kategori');

        }
    }
}