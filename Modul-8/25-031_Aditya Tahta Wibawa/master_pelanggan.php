<?php
include 'header.php';
wajib_owner();

if (isset($_POST['simpan'])) {
    $id     = $_POST['id_pelanggan'];
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $jk     = $_POST['jenis_kelamin'];
    $telp   = mysqli_real_escape_string($conn, $_POST['telp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, jenis_kelamin, telp, alamat) VALUES ('$nama', '$jk', '$telp', '$alamat')");
    } else {
        mysqli_query($conn, "UPDATE pelanggan SET nama_pelanggan='$nama', jenis_kelamin='$jk', telp='$telp', alamat='$alamat' WHERE id_pelanggan=$id");
    }
    header("Location: master_pelanggan.php");
    exit;
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan=" . (int)$_GET['hapus']);
    header("Location: master_pelanggan.php");
    exit;
}

$edit = ['id_pelanggan' => '', 'nama_pelanggan' => '', 'jenis_kelamin' => 'L', 'telp' => '', 'alamat' => ''];
if (isset($_GET['edit'])) {
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan=" . (int)$_GET['edit']));
}
?>

<h4>Data Master Pelanggan</h4>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><?= $edit['id_pelanggan'] ? 'Edit Pelanggan' : 'Tambah Pelanggan'; ?></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id_pelanggan" value="<?= $edit['id_pelanggan']; ?>">
                    <div class="mb-2">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" value="<?= $edit['nama_pelanggan']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="L" <?= $edit['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?= $edit['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
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
                    <?php if ($edit['id_pelanggan']): ?><a href="master_pelanggan.php" class="btn btn-secondary">Batal</a><?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>No</th><th>Nama</th><th>JK</th><th>Telp</th><th>Alamat</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($conn, "SELECT * FROM pelanggan");
                while ($r = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($r['nama_pelanggan']); ?></td>
                    <td><?= $r['jenis_kelamin']; ?></td>
                    <td><?= htmlspecialchars($r['telp']); ?></td>
                    <td><?= htmlspecialchars($r['alamat']); ?></td>
                    <td>
                        <a href="?edit=<?= $r['id_pelanggan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?hapus=<?= $r['id_pelanggan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelanggan?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>