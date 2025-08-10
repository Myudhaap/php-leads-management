<?php
namespace App\Controllers;

use App\Models\Sales;
use PDO;

class SalesController
{
    private $model;

    public function __construct(PDO $pdo){
        $this->model = new Sales($pdo);
    }

    public function index(){
        return $this->model->getAll();
    }
}