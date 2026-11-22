<?php
include_once 'koneksi.php';
include_once 'auth.php';
wajib_login();

$tgl_awal  = mysqli_real_escape_string($conn, $_GET['tgl_awal'] ?? date('Y-m-01'));
$tgl_akhir = mysqli_real_escape_string($conn, $_GET['tgl_akhir'] ?? date('Y-m-d'));

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan_{$tgl_awal}_sd_{$tgl_akhir}.xls");
?>

<h3>Laporan Penjualan</h3>
<p>Periode: <?= $tgl_awal; ?> s/d <?= $tgl_akhir; ?></p>

<table border="1">
    <thead>
        <tr style="background-color: #d9edf7; font-weight: bold;">
            <th>No</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Kasir / User</th>
            <th>Total Transaksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $total_pendapatan = 0;
        $sql = "SELECT t.*, p.nama_pelanggan, u.nama AS nama_user 
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN user u ON t.id_user = u.id_user
                WHERE t.tgl_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir'
                ORDER BY t.tgl_transaksi DESC, t.id_transaksi DESC";
        $q = mysqli_query($conn, $sql);
        while ($r = mysqli_fetch_assoc($q)):
            $total_pendapatan += $r['total'];
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $r['tgl_transaksi']; ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan'] ?? 'Umum'); ?></td>
            <td><?= htmlspecialchars($r['nama_user'] ?? '-'); ?></td>
            <td><?= $r['total']; ?></td>
        </tr>
        <?php endwhile; ?>
        <tr style="background-color: #dff0d8; font-weight: bold;">
            <td colspan="4" align="right">Total Pendapatan:</td>
            <td><?= $total_pendapatan; ?></td>
        </tr>
    </tbody>
</table>