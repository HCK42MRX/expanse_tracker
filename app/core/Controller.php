<?php

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        require_once '../app/views/' . $view . '.php';
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    protected function handleValidationErrors($errors)
    {
        // 3. Mengirim array error langsung ke Flasher
        Flasher::setFlash('Validasi Gagal', $errors, 'danger');
    }

    /**
     * Mengarahkan pengguna kembali ke halaman transaksi.
     */
    protected function redirectBack($url = '')
    {
        header('Location: ' . BASEURL . '/' . str_replace('/', '', $url));
        exit;
    }
}