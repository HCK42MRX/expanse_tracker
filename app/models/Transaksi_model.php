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
        $query = "INSERT INTO transaksi (kategori_id, deskripsi, jumlah) VALUES (:kategori_id, :deskripsi, :jumlah)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kategori_id', $data['kategori_id']);
        $stmt->bindValue(':deskripsi', $data['deskripsi']);
        $stmt->bindValue(':jumlah', $data['jumlah']);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getSumTransaksi()
    {
        $query = "SELECT SUM(jumlah) as total FROM transaksi";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }


}