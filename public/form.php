<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database as ConfigDatabase;
use App\Controllers\ProdukController;
use App\Controllers\SalesController;
use App\Controllers\LeadsController;

$db = (new ConfigDatabase())->connect();
$productController = new ProdukController($db);
$salesController = new SalesController($db);
$leadsController = new LeadsController($db);

$products = $productController->index();
$salesList = $salesController->index();
$leadsList = $leadsController->index();

$id = $_GET['id'] ?? null;
$lead = null;

if ($id) {
    $lead = $leadsController->getById($id);
    if (!$lead) {
        die("Data leads not found");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'tanggal' => $_POST['tanggal'],
        'id_sales' => $_POST['sales'],
        'nama_lead' => $_POST['namaLead'],
        'id_produk' => $_POST['produk'],
        'no_wa' => $_POST['noWa'],
        'kota' => $_POST['kota'],
    ];

    if ($id) {
        $leadsController->update($id, $data);
        $_SESSION['success'] = "Leads successfully added!";
        header("Location: index.php");
    } else {
        $leadsController->store($data);
        $_SESSION['success'] = "Leads successfully updated!";
        header("Location: index.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link href="./styles.css" rel="stylesheet"/>
</head>
<body>
    <header class="header">
        <nav class="navbar bg-primary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand fs-3" href="#">
                    Leads Management
                </a>
            </div>
        </nav>
    </header>
    <main class="main p-4 bg-body-tertiary h-100">
        <h1><?= $id ? 'Edit' : 'Add' ?> Form Leads</h1>

        <div class="card shadow-sm p-4" style="border:none">
            <form method="POST" class="row g-3 needs-validation" novalidate>
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($lead['tanggal'] ?? '') ?>" required>
                    <div class="invalid-feedback">
                        Tanggal is required
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="sales" class="form-label">Sales</label>
                    <select class="form-select" class="form-control" name="sales" aria-label="Pilih Sales" required>
                        <option value="">-- Pilih Sales --</option>
                        <?php foreach ($salesList as $sales): ?>
                            <option value="<?= htmlspecialchars($sales->id_sales ?? '') ?>" <?= ($lead['id_sales'] ?? '') == $sales->id_sales ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sales->nama_sales ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Sales is required
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="namaLead" class="form-label">Nama Lead</label>
                    <input type="text" class="form-control" id="namaLead" name="namaLead" value="<?= htmlspecialchars($lead['nama_lead'] ?? '') ?>"  required>
                    <div class="invalid-feedback">
                        Nama Lead is required
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="produk" class="form-label">Produk</label>
                    <select class="form-select" class="form-control" name="produk" aria-label="Pilih Sales" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= htmlspecialchars($product->id_produk ?? '') ?>" <?= ($lead['id_produk'] ?? '') == $product->id_produk ? 'selected' : '' ?>>
                                <?= htmlspecialchars($product->nama_produk ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Produk is required
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="noWa" class="form-label">No. Whatsapp</label>
                    <input type="text" class="form-control" id="noWa" name="noWa" value="<?= htmlspecialchars($lead['no_wa'] ?? '') ?>"  required>
                    <div class="invalid-feedback">
                        No Whatsapp is required
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="kota" class="form-label">Kota</label>
                    <input type="text" class="form-control" id="kota" name="kota" value="<?= htmlspecialchars($lead['kota'] ?? '') ?>"  required>
                    <div class="invalid-feedback">
                        Kota is required
                    </div>
                </div>

                <div class="col-12 d-flex gap-4">
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
    <script src="./app.js"></script>
</body>
</html>