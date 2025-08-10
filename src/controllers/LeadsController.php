<?php
namespace App\Controllers;

use App\Models\Entity\LeadsEntity;
use App\Models\Leads;
use PDO;

class LeadsController
{
    private $model;

    public function __construct(PDO $pdo){
        $this->model = new Leads($pdo);
    }

    public function index($filter = null){
        return $this->model->getAll($filter);
    }

     public function getById(int $id)
    {
        return $this->model->findById($id);
    }

    public function store(array $data): bool
    {
        $lead = new LeadsEntity();
        $lead->tanggal = $data['tanggal'];
        $lead->id_sales = (int)$data['id_sales'];
        $lead->nama_lead = $data['nama_lead'];
        $lead->id_produk = (int)$data['id_produk'];
        $lead->no_wa = $data['no_wa'];
        $lead->kota = $data['kota'];

        return $this->model->insert($lead);
    }

    public function update(int $id, array $data): bool
    {
        $lead = new LeadsEntity();
        $lead->id_leads = $id;
        $lead->tanggal = $data['tanggal'];
        $lead->id_sales = (int)$data['id_sales'];
        $lead->nama_lead = $data['nama_lead'];
        $lead->id_produk = (int)$data['id_produk'];
        $lead->no_wa = $data['no_wa'];
        $lead->kota = $data['kota'];

        return $this->model->update($lead);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}