<?php
include 'header.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_POST['tambah_item'])) {
    $id_barang = (int)$_POST['id_barang'];
    $qty       = (int)$_POST['qty'];

    $brg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE id_barang=$id_barang"));
    if ($brg && $qty > 0 && $qty <= $brg['stok']) {
        if (isset($_SESSION['keranjang'][$id_barang])) {
            $_SESSION['keranjang'][$id_barang]['qty'] += $qty;
        } else {
            $_SESSION['keranjang'][$id_barang] = [
                'nama_barang' => $brg['nama_barang'],
                'harga'       => $brg['harga'],
                'qty'         => $qty
            ];
        }
    } else {
        echo "<script>alert('Stok tidak mencukupi!');</script>";
    }
}

if (isset($_GET['hapus_item'])) {
    unset($_SESSION['keranjang'][(int)$_GET['hapus_item']]);
    header("Location: transaksi.php");
    exit;
}

if (isset($_POST['simpan_transaksi']) && !empty($_SESSION['keranjang'])) {
    $tgl          = $_POST['tgl_transaksi'];
    $id_pelanggan = (int)$_POST['id_pelanggan'];
    $id_user      = (int)$_SESSION['id_user'];
    $total        = (int)$_POST['total_bayar'];

    mysqli_query($conn, "INSERT INTO transaksi (tgl_transaksi, id_pelanggan, id_user, total) VALUES ('$tgl', $id_pelanggan, $id_user, $total)");
    $id_transaksi = mysqli_insert_id($conn);

    foreach ($_SESSION['keranjang'] as $id_brg => $item) {
        $qty      = $item['qty'];
        $subtotal = $item['harga'] * $qty;
        mysqli_query($conn, "INSERT INTO transaksi_detail (id_transaksi, id_barang, qty, subtotal) VALUES ($id_transaksi, $id_brg, $qty, $subtotal)");
        mysqli_query($conn, "UPDATE barang SET stok = stok - $qty WHERE id_barang = $id_brg");
    }

    $_SESSION['keranjang'] = [];
    echo "<script>alert('Transaksi berhasil disimpan!'); window.location='transaksi.php';</script>";
    exit;
}
?>

<h4>Transaksi Penjualan</h4>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Pilih Barang</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-2">
                        <label>Barang</label>
                        <select name="id_barang" class="form-select" required>
                            <?php
                            $brg = mysqli_query($conn, "SELECT * FROM barang WHERE stok > 0");
                            while ($b = mysqli_fetch_assoc($brg)):
                            ?>
                            <option value="<?= $b['id_barang']; ?>">
                                <?= htmlspecialchars($b['nama_barang']); ?> (Stok: <?= $b['stok']; ?> - Rp <?= number_format($b['harga'],0,',','.'); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Jumlah (Qty)</label>
                        <input type="number" name="qty" class="form-control" value="1" min="1" required>
                    </div>
                    <button type="submit" name="tambah_item" class="btn btn-success w-100">+ Tambah ke Keranjang</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Keranjang Belanja</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        foreach ($_SESSION['keranjang'] as $id_brg => $item):
                            $sub = $item['harga'] * $item['qty'];
                            $grand_total += $sub;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nama_barang']); ?></td>
                            <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td><?= $item['qty']; ?></td>
                            <td>Rp <?= number_format($sub, 0, ',', '.'); ?></td>
                            <td><a href="?hapus_item=<?= $id_brg; ?>" class="btn btn-danger btn-sm">Hapus</a></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-secondary fw-bold">
                            <td colspan="3" class="text-end">Total Belanja:</td>
                            <td colspan="2">Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if (!empty($_SESSION['keranjang'])): ?>
                <form method="POST" class="row g-2 align-items-end">
                    <input type="hidden" name="total_bayar" value="<?= $grand_total; ?>">
                    <div class="col-md-4">
                        <label>Tanggal Transaksi</label>
                        <input type="date" name="tgl_transaksi" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Pelanggan</label>
                        <select name="id_pelanggan" class="form-select" required>
                            <?php
                            $pel = mysqli_query($conn, "SELECT * FROM pelanggan");
                            while ($p = mysqli_fetch_assoc($pel)):
                            ?>
                            <option value="<?= $p['id_pelanggan']; ?>"><?= htmlspecialchars($p['nama_pelanggan']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="simpan_transaksi" class="btn btn-primary w-100">Simpan Transaksi</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>