<?php

class Dashboard extends Controller
{
    public function index()
    {
        // Panggil model sekali saja
        $transaksiModel = $this->model('transaksi_model');

        // Panggil method baru yang efisien, HANYA 1x ke database! 🚀
        $data['totals'] = $transaksiModel->getTransactionTotals();

        // Siapkan judul
        $data['judul'] = 'Dashboard';

        // Tampilkan view dengan data yang sudah siap
        $this->view('layouts/header', $data);
        $this->view('dashboard', $data);
        $this->view('layouts/footer');
    }
}