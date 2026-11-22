<?php include 'header.php'; ?>

<div class="p-4 bg-light border rounded-3">
    <h3>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h3>
    <p class="mb-1">Anda login sebagai <strong><?= ($_SESSION['level'] == 1) ? 'Level 1 (Owner)' : 'Level 2 (Kasir)'; ?></strong>.</p>
    <hr>
    <p class="text-muted mb-0">
        Gunakan menu navigasi di atas untuk mengelola sistem penjualan sesuai hak akses Anda.
    </p>
</div>

<?php include 'footer.php'; ?>