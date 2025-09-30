<?php

class kategori_model extends Model
{
    public function getAllKategori()
    {
        $query = "SELECT * FROM kategori ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdKategori()
    {
        $query = "SELECT id FROM kategori";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function postNewKategori($data)
    {
        $query = "INSERT INTO kategori (nama_kategori, jenis) VALUES (:nama_kategori, :jenis)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nama_kategori', $data['nama_kategori']);
        $stmt->bindValue(':jenis', $data['jenis']);
        $stmt->execute();

        return $stmt->rowCount();
    }
}