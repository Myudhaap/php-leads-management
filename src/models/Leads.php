<?php
namespace App\Models;

use App\Models\Entity\LeadsEntity;
use PDO;

class Leads 
{
    private $conn;
    private $table = "leads";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($filter): array
    {
        $query =  <<<SQL
            SELECT * 
            FROM {$this->table} l
            JOIN produk p ON l.id_produk = p.id_produk
            JOIN sales s ON l.id_sales = s.id_sales
        SQL;

        $conditions = [];
        $params = [];

        if (!empty($filter['tanggal'])) {
            $conditions[] = "MONTH(l.tanggal) = :tanggal";
            $params[':tanggal'] = $filter['tanggal'];
        }

        if (!empty($filter['sales'])) {
            $conditions[] = "l.id_sales = :id_sales";
            $params[':id_sales'] = $filter['sales'];
        }

        if (!empty($filter['produk'])) {
            $conditions[] = "l.id_produk = :id_produk";
            $params[':id_produk'] = $filter['produk'];
        }

        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY l.tanggal DESC";

        // echo $query;
        // echo "<br>";
        // print_r($filter);

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // print_r($res);
        return $res;
    }

    public function findById(int $id)
    {
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM {$this->table} l
            JOIN produk p ON l.id_produk = p.id_produk
            JOIN sales s ON l.id_sales = s.id_sales
            WHERE l.id_leads = :id
            ORDER BY l.tanggal DESC
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch() ?: null;
    }

     public function insert(LeadsEntity $lead): bool
    {
        $sql = "INSERT INTO {$this->table} (tanggal, id_sales, nama_lead, id_produk, no_wa, kota)
                VALUES (:tanggal, :id_sales, :nama_lead, :id_produk, :no_wa, :kota)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tanggal' => $lead->tanggal,
            ':id_sales' => $lead->id_sales,
            ':nama_lead' => $lead->nama_lead,
            ':id_produk' => $lead->id_produk,
            ':no_wa' => $lead->no_wa,
            ':kota' => $lead->kota
        ]);
    }

    public function update(LeadsEntity $lead): bool
    {
        $sql = "UPDATE {$this->table}
                SET tanggal = :tanggal,
                    id_sales = :id_sales,
                    nama_lead = :nama_lead,
                    id_produk = :id_produk,
                    no_wa = :no_wa,
                    kota = :kota
                WHERE id_leads = :id_leads";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':tanggal' => $lead->tanggal,
            ':id_sales' => $lead->id_sales,
            ':nama_lead' => $lead->nama_lead,
            ':id_produk' => $lead->id_produk,
            ':no_wa' => $lead->no_wa,
            ':kota' => $lead->kota,
            ':id_leads' => $lead->id_leads
        ]);
    }

    public function delete(int $id): bool {
        $sql = <<<SQL
            DELETE FROM leads
            WHERE id_leads = :id_leads
            SQL;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_leads' => $id
        ]);
    }
}