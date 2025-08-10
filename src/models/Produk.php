<?php
namespace App\Models;

use App\models\entity\ProdukEntity;
use PDO;

class Produk 
{
    private $conn;
    private $table = "produk";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_produk DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, ProdukEntity::class);
    }
}