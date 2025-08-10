<?php
namespace App\Models;

use App\models\entity\SalesEntity;
use PDO;

class Sales
{
    private $conn;
    private $table = "sales";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_sales DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, SalesEntity::class);
    }
}

