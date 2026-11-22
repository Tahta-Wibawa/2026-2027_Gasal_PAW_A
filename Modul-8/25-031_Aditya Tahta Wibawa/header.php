<?php
include_once 'koneksi.php';
include_once 'auth.php';
wajib_login(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Sistem Penjualan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <!-- Menu Data Master HANYA muncul jika Level == 1 (Owner) -->
                <?php if ($_SESSION['level'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Data Master
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="master_barang.php">Data Barang</a></li>
                        <li><a class="dropdown-item" href="master_supplier.php">Data Supplier</a></li>
                        <li><a class="dropdown-item" href="master_pelanggan.php">Data Pelanggan</a></li>
                        <li><a class="dropdown-item" href="master_user.php">Data User</a></li>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="transaksi.php">Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laporan.php">Laporan</a>
                </li>
            </ul>

            
            <div class="d-flex align-items-center text-white">
                <span class="me-3">
                    Halo, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>
                    <span class="badge bg-info text-dark">
                        <?= ($_SESSION['level'] == 1) ? 'Owner' : 'Kasir'; ?>
                    </span>
                </span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin logout?')">Logout</a>
            </div>
        </div>
    </div>
</nav>
<div class="container">