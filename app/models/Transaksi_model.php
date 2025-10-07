<?php

class transaksi_model extends Model
{
    /**
     * Mengambil semua transaksi dengan opsi filter.
     * Method ini menggantikan getAllTransaksi() yang lama.
     *
     * @param array $filters Opsi filter, bisa berisi: 'tanggal_mulai', 'tanggal_akhir', 'kategori_id', 'jenis'.
     * @return array Daftar transaksi.
     */
    public function getFilteredTransaksi(array $filters = []): array
    {
        // Query dasar
        $query = "SELECT t.*, k.nama_kategori, k.jenis as jenis_kategori 
                  FROM transaksi t 
                  LEFT JOIN kategori k ON t.kategori_id = k.id";

        $conditions = [];
        $params = [];

        // Menambahkan filter berdasarkan input
        if (!empty($filters['tanggal_mulai'])) {
            $conditions[] = "t.tanggal_transaksi >= :tanggal_mulai";
            $params[':tanggal_mulai'] = $filters['tanggal_mulai'];
        }

        if (!empty($filters['tanggal_akhir'])) {
            $conditions[] = "t.tanggal_transaksi <= :tanggal_akhir";
            $params[':tanggal_akhir'] = $filters['tanggal_akhir'];
        }

        if (!empty($filters['kategori_id'])) {
            $conditions[] = "t.kategori_id = :kategori_id";
            $params[':kategori_id'] = $filters['kategori_id'];
        }

        // Filter 'jenis' (0 untuk pengeluaran, 1 untuk pemasukan)
        // Mengecek dengan isset() karena '0' dianggap empty() oleh PHP
        if (isset($filters['jenis']) && $filters['jenis'] !== '') {
            $conditions[] = "k.jenis = :jenis";
            $params[':jenis'] = $filters['jenis'];
        }

        // Gabungkan semua kondisi filter ke dalam query jika ada
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        // Tambahkan pengurutan
        $query .= " ORDER BY t.tanggal_transaksi DESC, t.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menghitung total pemasukan, pengeluaran, dan saldo akhir dengan filter.
     * Method ini jauh lebih efisien dan menggantikan getSumTransaksi() yang lama.
     *
     * @param array $filters Opsi filter yang sama dengan getFilteredTransaksi().
     * @return array Berisi 'total_pemasukan', 'total_pengeluaran', 'total_saldo'.
     */
    public function getTransactionTotals(array $filters = []): array
    {
        // Query dasar untuk agregasi kondisional
        $query = "SELECT 
                    COALESCE(SUM(CASE WHEN k.jenis = 1 THEN t.jumlah ELSE 0 END), 0) as total_pemasukan,
                    COALESCE(SUM(CASE WHEN k.jenis = 0 THEN t.jumlah ELSE 0 END), 0) as total_pengeluaran
                  FROM transaksi t
                  LEFT JOIN kategori k ON t.kategori_id = k.id";

        // Logika filter di sini SAMA PERSIS dengan method getFilteredTransaksi
        $conditions = [];
        $params = [];

        if (!empty($filters['tanggal_mulai'])) {
            $conditions[] = "t.tanggal_transaksi >= :tanggal_mulai";
            $params[':tanggal_mulai'] = $filters['tanggal_mulai'];
        }
        if (!empty($filters['tanggal_akhir'])) {
            $conditions[] = "t.tanggal_transaksi <= :tanggal_akhir";
            $params[':tanggal_akhir'] = $filters['tanggal_akhir'];
        }
        if (!empty($filters['kategori_id'])) {
            $conditions[] = "t.kategori_id = :kategori_id";
            $params[':kategori_id'] = $filters['kategori_id'];
        }
        if (isset($filters['jenis']) && $filters['jenis'] !== '') {
            $conditions[] = "k.jenis = :jenis";
            $params[':jenis'] = $filters['jenis'];
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $totals = $stmt->fetch(PDO::FETCH_ASSOC);

        // Hitung saldo akhir
        $totals['total_saldo'] = $totals['total_pemasukan'] + $totals['total_pengeluaran'];

        return $totals;
    }

    /**
     * Menyimpan transaksi baru ke database.
     * (Tidak ada perubahan signifikan, sudah cukup baik)
     *
     * @param array $data Data transaksi baru.
     * @return int Jumlah baris yang terpengaruh.
     */
    public function postNewTransaksi(array $data): int
    {
        $query = "INSERT INTO transaksi (kategori_id, deskripsi, tanggal_transaksi, jumlah) 
                  VALUES (:kategori_id, :deskripsi, :tanggal_transaksi, :jumlah)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kategori_id', $data['kategori_id']);
        $stmt->bindValue(':deskripsi', $data['deskripsi']);
        $stmt->bindValue(':tanggal_transaksi', $data['tanggal']); // Sesuai dengan form
        $stmt->bindValue(':jumlah', $data['jumlah']);
        $stmt->execute();

        return $stmt->rowCount();
    }
}