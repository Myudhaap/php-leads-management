<?php
namespace App\Controllers;

use App\Models\Produk;
use PDO;

class ProdukController
{
    private $model;

    public function __construct(PDO $pdo){
        $this->model = new Produk($pdo);
    }

    public function index(){
        return $this->model->getAll();
    }
}