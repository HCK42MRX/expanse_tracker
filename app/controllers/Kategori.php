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
        if ($this->model('Kategori_model')->postNewKategori($_POST) > 0) {
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
            header('location: ' . BASEURL . '/kategori');
            exit;
        } else {
            Flasher::setFlash('gagal', 'gagal ditambahkan', 'danger');
            header('location: ' . BASEURL . '/kategori');
            exit;
        }
    }
}