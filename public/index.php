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

$salesList = $salesController->index();
$products = $productController->index();

$successCode = $_GET['success'] ?? null;

$tanggalFilter = $_GET['tanggal'] ?? null;
$salesFilter   = $_GET['sales'] ?? null;
$produkFilter  = $_GET['produk'] ?? null;

if ($tanggalFilter || $salesFilter || $produkFilter) {
     $filter = [
        'tanggal' => $tanggalFilter ?? null,
        'sales'   => $salesFilter ?? null,
        'produk'  => $produkFilter ?? null,
    ];
    $leadsList = $leadsController->index($filter);
} else {
    $leadsList = $leadsController->index();
}


if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'filter') {
    header("Location: index.php?tanggal={$tanggalFilter}&sales={$salesFilter}&produk={$produkFilter}");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_leads'])) {
    $leadsController->delete($_POST['id_leads']);
    $_SESSION['success'] = "Leads successfully deleted!";
    if ($tanggalFilter || $salesFilter || $produkFilter) {
        header("Location: index.php?tanggal={$tanggalFilter}&sales={$salesFilter}&produk={$produkFilter}");
    }else {
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
        <h1>Lead List</h1>

        <div class="card shadow-sm p-4" style="width: 100%; height: auto; border:none">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div class="d-flex">
                    <form method="GET" class="row g-3" novalidate>
                        <div class="col-md-3">
                            <label for="tanggal" class="form-label">Bulan</label>
                            <select id="tanggal" name="tanggal" class="form-select">
                                <option value="">-- Pilih Bulan --</option>
                                <option value="01" <?= ($tanggalFilter ?? '') === '01' ? 'selected' : '' ?>>Januari</option>
                                <option value="02" <?= ($tanggalFilter ?? '') === '02' ? 'selected' : '' ?>>Februari</option>
                                <option value="03" <?= ($tanggalFilter ?? '') === '03' ? 'selected' : '' ?>>Maret</option>
                                <option value="04" <?= ($tanggalFilter ?? '') === '04' ? 'selected' : '' ?>>April</option>
                                <option value="05" <?= ($tanggalFilter ?? '') === '05' ? 'selected' : '' ?>>Mei</option>
                                <option value="06" <?= ($tanggalFilter ?? '') === '06' ? 'selected' : '' ?>>Juni</option>
                                <option value="07" <?= ($tanggalFilter ?? '') === '07' ? 'selected' : '' ?>>Juli</option>
                                <option value="08" <?= ($tanggalFilter ?? '') === '08' ? 'selected' : '' ?>>Agustus</option>
                                <option value="09" <?= ($tanggalFilter ?? '') === '09' ? 'selected' : '' ?>>September</option>
                                <option value="10" <?= ($tanggalFilter ?? '') === '10' ? 'selected' : '' ?>>Oktober</option>
                                <option value="11" <?= ($tanggalFilter ?? '') === '11' ? 'selected' : '' ?>>November</option>
                                <option value="12" <?= ($tanggalFilter ?? '') === '12' ? 'selected' : '' ?>>Desember</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sales" class="form-label">Sales</label>
                            <select class="form-select" class="form-control" name="sales" aria-label="Pilih Sales">
                                <option value="">-- Pilih Sales --</option>
                                <?php foreach ($salesList as $sales): ?>
                                    <option value="<?= htmlspecialchars($sales->id_sales ?? '') ?>" <?= ($salesFilter ?? '') == $sales->id_sales ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sales->nama_sales ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="produk" class="form-label">Produk</label>
                            <select class="form-select" class="form-control" name="produk" aria-label="Pilih Sales">
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= htmlspecialchars($product->id_produk ?? '') ?>" <?= ($produkFilter ?? '') == $product->id_produk ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($product->nama_produk ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary" name="action" value="filter">Filter</button>
                            <button type="submit" class="btn btn-danger" name="action" value="reset">Reset</button>
                        </div>
                    </form>
                </div>
                <a href="form.php" class="btn btn-primary">Add</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">ID Input</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Sales</th>
                    <th scope="col">Produk</th>
                    <th scope="col">Nama Leads</th>
                    <th scope="col">No Wa</th>
                    <th scope="col">Kota</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($leadsList)): ?>
                        <?php foreach ($leadsList as $index => $lead): ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td><?= htmlspecialchars($lead['id_leads']) ?></td>
                                <td><?= htmlspecialchars($lead['tanggal']) ?></td>
                                <td><?= htmlspecialchars($lead['nama_sales'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($lead['nama_produk'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($lead['nama_lead']) ?></td>
                                <td><?= htmlspecialchars($lead['no_wa']) ?></td>
                                <td><?= htmlspecialchars($lead['kota']) ?></td>
                                <td>
                                    <a href="form.php?id=<?= $lead['id_leads'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form id="deleteForm<?= $lead['id_leads'] ?>" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_leads" value="<?= $lead['id_leads'] ?>">
                                        <button type="button" class="btn btn-danger btn-sm"
                                                onclick="if(confirm('Apakah yakin ingin menghapus data ini?')) { document.getElementById('deleteForm<?= $lead['id_leads'] ?>').submit(); }">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Leads Not Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</body>
</html>