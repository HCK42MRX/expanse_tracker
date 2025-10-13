<?php

class Flasher
{
    public static function setFlash($judul, $pesan, $tipe)
    {
        $_SESSION['flash'] = [
            'judul' => $judul,
            'pesan' => $pesan, // Pesan bisa berupa string atau array
            'tipe' => $tipe
        ];
    }

    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            $judul = $_SESSION['flash']['judul'];
            $pesan = $_SESSION['flash']['pesan']; // Ambil pesan
            $tipe = $_SESSION['flash']['tipe'];

            // Mulai Alert Bootstrap
            echo '<div class="alert alert-' . $tipe . ' alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">' . htmlspecialchars($judul) . '</h5>';

            // ===== INTI PERUBAHANNYA ADA DI SINI =====
            // Cek apakah $pesan adalah sebuah array
            if (is_array($pesan)) {
                // Jika ya, buat daftar HTML (unordered list)
                echo '<ul>';
                foreach ($pesan as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul>';
            } else {
                // Jika bukan array (hanya string biasa), tampilkan langsung
                echo '<p class="mb-0">' . htmlspecialchars($pesan) . '</p>';
            }
            // ===========================================

            // Lanjutan Alert Bootstrap
            echo '  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

            // Hapus session flash setelah ditampilkan
            unset($_SESSION['flash']);
        }
    }
}