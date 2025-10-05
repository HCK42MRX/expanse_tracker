<?php

class transaksi_model extends Model
{
    public function getAllTransaksi()
    {
        $query = "SELECT t.*, k.nama_kategori, k.jenis as jenis_kategori FROM transaksi t LEFT JOIN kategori k ON t.kategori_id = k.id ORDER BY t.created_at DESC LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function postNewTransaksi($data)
    {
        $query = "INSERT INTO transaksi (kategori_id, deskripsi, tanggal_transaksi,jumlah) VALUES (:kategori_id, :deskripsi, :tanggal_transaksi, :jumlah)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kategori_id', $data['kategori_id']);
        $stmt->bindValue(':deskripsi', $data['deskripsi']);
        $stmt->bindValue(':tanggal_transaksi', $data['tanggal']);
        $stmt->bindValue(':jumlah', $data['jumlah']);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getSumTransaksi($tanggal_awal = null, $tanggal_akhir = null, $jenis = null)
    {
        $query = "SELECT SUM(jumlah) as total FROM transaksi";
        $params = [];
        $conditions = [];
        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $query .= " WHERE created_at BETWEEN :tanggal_awal AND :tanggal_akhir";
            $params = [':tanggal_awal' => $tanggal_awal, ':tanggal_akhir' => $tanggal_akhir];
        }

        if ($jenis === 'pemasukan') {
            $conditions[] = "jumlah > 0";
        } else if ($jenis === 'pengeluaran') {
            $conditions[] = "jumlah < 0";
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }


}