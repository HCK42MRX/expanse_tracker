<?php

class transaksi_model extends Model
{
    /**
     * [REFAKTOR] Method private untuk membangun kondisi filter secara terpusat.
     * Mencegah duplikasi kode di method lain.
     *
     * @param array $filters
     * @return array Berisi ['conditions' => string, 'params' => array]
     */
    private function _buildFilterConditions(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['tanggal_mulai'])) {
            $conditions[] = "t.tanggal_transaksi >= :tanggal_mulai";
            $params[':tanggal_mulai'] = $filters['tanggal_mulai'];
        }

        if (!empty($filters['tanggal_akhir'])) {
            // [PERBAIKAN 1] Memastikan tanggal akhir mencakup seluruh hari.
            $conditions[] = "t.tanggal_transaksi <= :tanggal_akhir";
            $params[':tanggal_akhir'] = $filters['tanggal_akhir'] . ' 23:59:59';
        }

        if (!empty($filters['kategori_id'])) {
            $conditions[] = "t.kategori_id = :kategori_id";
            $params[':kategori_id'] = $filters['kategori_id'];
        }

        $whereClause = !empty($conditions) ? " WHERE " . implode(' AND ', $conditions) : "";

        return [
            'clause' => $whereClause,
            'params' => $params
        ];
    }

    /**
     * Mengambil semua transaksi dengan opsi filter.
     */
    public function getFilteredTransaksi(array $filters = []): array
    {
        $query = "SELECT t.*, k.nama_kategori, k.jenis as jenis_kategori 
                  FROM transaksi t 
                  LEFT JOIN kategori k ON t.kategori_id = k.id";

        // [REFAKTOR 2] Menggunakan method helper untuk filter
        $filterData = $this->_buildFilterConditions($filters);
        $query .= $filterData['clause'];

        $query .= " ORDER BY t.tanggal_transaksi DESC, t.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($filterData['params']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Menghitung total pemasukan, pengeluaran, dan saldo akhir dengan filter.
     */
    public function getTransactionTotals(array $filters = []): array
    {
        $query = "SELECT 
                    COALESCE(SUM(CASE WHEN k.jenis = 1 THEN t.jumlah ELSE 0 END), 0) as total_pemasukan,
                    COALESCE(SUM(CASE WHEN k.jenis = 0 THEN t.jumlah ELSE 0 END), 0) as total_pengeluaran
                  FROM transaksi t
                  LEFT JOIN kategori k ON t.kategori_id = k.id";

        // [REFAKTOR 2] Menggunakan method helper yang sama
        $filterData = $this->_buildFilterConditions($filters);
        $query .= $filterData['clause'];

        $stmt = $this->db->prepare($query);
        $stmt->execute($filterData['params']);
        $totals = $stmt->fetch(PDO::FETCH_ASSOC);

        // [PERBAIKAN 3] Logika perhitungan saldo yang benar.
        $totals['total_saldo'] = $totals['total_pemasukan'] - abs($totals['total_pengeluaran']);

        return $totals;
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function postNewTransaksi(array $data): int
    {
        $query = "INSERT INTO transaksi (kategori_id, deskripsi, tanggal_transaksi, jumlah) 
                  VALUES (:kategori_id, :deskripsi, :tanggal_transaksi, :jumlah)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kategori_id', $data['kategori_id']);
        $stmt->bindValue(':deskripsi', $data['deskripsi']);
        // [SARAN 4] Pastikan key 'tanggal' sesuai dengan nama form Anda.
        // Jika memungkinkan, samakan dengan nama kolom: $data['tanggal_transaksi']
        $stmt->bindValue(':tanggal_transaksi', $data['tanggal']);
        $stmt->bindValue(':jumlah', $data['jumlah']);
        $stmt->execute();

        return $stmt->rowCount();
    }
}