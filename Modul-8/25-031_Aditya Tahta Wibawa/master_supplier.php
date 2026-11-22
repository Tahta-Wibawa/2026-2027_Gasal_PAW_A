<?php
include 'header.php';
wajib_owner();

if (isset($_POST['simpan'])) {
    $id     = $_POST['id_supplier'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
    $telp   = mysqli_real_escape_string($conn, $_POST['telp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO supplier (nama_supplier, telp, alamat) VALUES ('$nama', '$telp', '$alamat')");
    } else {
        mysqli_query($conn, "UPDATE supplier SET nama_supplier='$nama', telp='$telp', alamat='$alamat' WHERE id_supplier=$id");
    }
    header("Location: master_supplier.php");
    exit;
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier=" . (int)$_GET['hapus']);
    header("Location: master_supplier.php");
    exit;
}

$edit = ['id_supplier' => '', 'nama_supplier' => '', 'telp' => '', 'alamat' => ''];
if (isset($_GET['edit'])) {
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM supplier WHERE id_supplier=" . (int)$_GET['edit']));
}
?>

<h4>Data Master Supplier</h4>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><?= $edit['id_supplier'] ? 'Edit Supplier' : 'Tambah Supplier'; ?></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id_supplier" value="<?= $edit['id_supplier']; ?>">
                    <div class="mb-2">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" value="<?= $edit['nama_supplier']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>No. Telepon</label>
                        <input type="text" name="telp" class="form-control" value="<?= $edit['telp']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required><?= $edit['alamat']; ?></textarea>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    <?php if ($edit['id_supplier']): ?><a href="master_supplier.php" class="btn btn-secondary">Batal</a><?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>No</th><th>Nama Supplier</th><th>Telp</th><th>Alamat</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($conn, "SELECT * FROM supplier");
                while ($r = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($r['nama_supplier']); ?></td>
                    <td><?= htmlspecialchars($r['telp']); ?></td>
                    <td><?= htmlspecialchars($r['alamat']); ?></td>
                    <td>
                        <a href="?edit=<?= $r['id_supplier']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?hapus=<?= $r['id_supplier']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus supplier?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>