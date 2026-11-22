<?php
include 'header.php';
wajib_owner();

if (isset($_POST['simpan'])) {
    $id     = $_POST['id_barang'];
    $kode   = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $harga  = (int)$_POST['harga'];
    $stok   = (int)$_POST['stok'];
    $id_sup = (int)$_POST['id_supplier'];

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO barang (kode_barang, nama_barang, harga, stok, id_supplier) VALUES ('$kode', '$nama', $harga, $stok, $id_sup)");
    } else {
        mysqli_query($conn, "UPDATE barang SET kode_barang='$kode', nama_barang='$nama', harga=$harga, stok=$stok, id_supplier=$id_sup WHERE id_barang=$id");
    }
    header("Location: master_barang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM barang WHERE id_barang=" . (int)$_GET['hapus']);
    header("Location: master_barang.php");
    exit;
}

$edit = ['id_barang' => '', 'kode_barang' => '', 'nama_barang' => '', 'harga' => '', 'stok' => '', 'id_supplier' => ''];
if (isset($_GET['edit'])) {
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM barang WHERE id_barang=" . (int)$_GET['edit']));
}
?>

<h4>Data Master Barang</h4>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><?= $edit['id_barang'] ? 'Edit Barang' : 'Tambah Barang'; ?></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id_barang" value="<?= $edit['id_barang']; ?>">
                    <div class="mb-2">
                        <label>Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" value="<?= $edit['kode_barang']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= $edit['nama_barang']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="<?= $edit['harga']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $edit['stok']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Supplier</label>
                        <select name="id_supplier" class="form-select" required>
                            <?php
                            $sup = mysqli_query($conn, "SELECT * FROM supplier");
                            while ($s = mysqli_fetch_assoc($sup)):
                            ?>
                            <option value="<?= $s['id_supplier']; ?>" <?= $edit['id_supplier'] == $s['id_supplier'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($s['nama_supplier']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    <?php if ($edit['id_barang']): ?><a href="master_barang.php" class="btn btn-secondary">Batal</a><?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Stok</th><th>Supplier</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT b.*, s.nama_supplier FROM barang b LEFT JOIN supplier s ON b.id_supplier = s.id_supplier");
                while ($r = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= htmlspecialchars($r['kode_barang']); ?></td>
                    <td><?= htmlspecialchars($r['nama_barang']); ?></td>
                    <td>Rp <?= number_format($r['harga'], 0, ',', '.'); ?></td>
                    <td><?= $r['stok']; ?></td>
                    <td><?= htmlspecialchars($r['nama_supplier'] ?? '-'); ?></td>
                    <td>
                        <a href="?edit=<?= $r['id_barang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?hapus=<?= $r['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>