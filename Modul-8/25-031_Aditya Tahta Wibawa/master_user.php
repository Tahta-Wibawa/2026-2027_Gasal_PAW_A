<?php
include 'header.php';
wajib_owner();

if (isset($_POST['simpan'])) {
    $id_user  = $_POST['id_user'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $level    = (int)$_POST['level'];

    if ($id_user == "") {
        $password = md5($_POST['password']);
        mysqli_query($conn, "INSERT INTO user (username, password, nama, level) VALUES ('$username', '$password', '$nama', '$level')");
    } else {
        if (!empty($_POST['password'])) {
            $password = md5($_POST['password']);
            mysqli_query($conn, "UPDATE user SET username='$username', password='$password', nama='$nama', level='$level' WHERE id_user=$id_user");
        } else {
            mysqli_query($conn, "UPDATE user SET username='$username', nama='$nama', level='$level' WHERE id_user=$id_user");
        }
    }
    header("Location: master_user.php");
    exit;
}


if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if ($id != $_SESSION['id_user']) { 
        mysqli_query($conn, "DELETE FROM user WHERE id_user=$id");
    }
    header("Location: master_user.php");
    exit;
}


$edit = ['id_user' => '', 'username' => '', 'nama' => '', 'level' => 2];
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user=$id"));
}
?>

<h4>Data Master User</h4>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><?= $edit['id_user'] ? 'Edit User' : 'Tambah User'; ?></div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="id_user" value="<?= $edit['id_user']; ?>">
                    <div class="mb-2">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= $edit['nama']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $edit['username']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label>Password <?= $edit['id_user'] ? '<small class="text-muted">(kosongkan jika tetap)</small>' : ''; ?></label>
                        <input type="password" name="password" class="form-control" <?= $edit['id_user'] ? '' : 'required'; ?>>
                    </div>
                    <div class="mb-3">
                        <label>Level</label>
                        <select name="level" class="form-select">
                            <option value="1" <?= $edit['level'] == 1 ? 'selected' : ''; ?>>1 - Owner</option>
                            <option value="2" <?= $edit['level'] == 2 ? 'selected' : ''; ?>>2 - Kasir</option>
                        </select>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    <?php if ($edit['id_user']): ?>
                        <a href="master_user.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>No</th><th>Nama</th><th>Username</th><th>Level</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($conn, "SELECT * FROM user ORDER BY id_user ASC");
                while ($r = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($r['nama']); ?></td>
                    <td><?= htmlspecialchars($r['username']); ?></td>
                    <td><?= $r['level'] == 1 ? '1 (Owner)' : '2 (Kasir)'; ?></td>
                    <td>
                        <a href="?edit=<?= $r['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php if ($r['id_user'] != $_SESSION['id_user']): ?>
                            <a href="?hapus=<?= $r['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>