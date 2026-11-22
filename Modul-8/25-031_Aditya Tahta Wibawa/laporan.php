<?php
include 'header.php';

$tgl_awal  = mysqli_real_escape_string($conn, $_GET['tgl_awal'] ?? date('Y-m-01'));
$tgl_akhir = mysqli_real_escape_string($conn, $_GET['tgl_akhir'] ?? date('Y-m-d'));


$label_tgl  = [];
$data_total = [];
$q_chart = mysqli_query($conn, "SELECT tgl_transaksi, SUM(total) AS total_harian 
                                FROM transaksi 
                                WHERE tgl_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir' 
                                GROUP BY tgl_transaksi 
                                ORDER BY tgl_transaksi ASC");
while ($c = mysqli_fetch_assoc($q_chart)) {
    $label_tgl[]  = $c['tgl_transaksi'];
    $data_total[] = (int)$c['total_harian'];
}
?>

<h4>Laporan Penjualan</h4>


<p class="d-none d-print-block mb-3">
    Periode: <strong><?= $tgl_awal; ?></strong> s/d <strong><?= $tgl_akhir; ?></strong>
</p>


<form method="GET" class="row g-2 my-3 align-items-end d-print-none">
    <div class="col-md-3">
        <label>Dari Tanggal</label>
        <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal; ?>">
    </div>
    <div class="col-md-3">
        <label>Sampai Tanggal</label>
        <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir; ?>">
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-primary">Filter</button>
        <button type="button" onclick="window.print()" class="btn btn-secondary">Cetak Laporan</button>
        <a href="export_excel.php?tgl_awal=<?= $tgl_awal; ?>&tgl_akhir=<?= $tgl_akhir; ?>" class="btn btn-success">
            Export Excel
        </a>
    </div>
</form>


<div class="card mb-4">
    <div class="card-header fw-bold">Grafik Pendapatan Harian</div>
    <div class="card-body">
        <canvas id="grafikPenjualan" height="80"></canvas>
    </div>
</div>


<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
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
            <td>Rp <?= number_format($r['total'], 0, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
        <tr class="table-success fw-bold">
            <td colspan="4" class="text-end">Total Pendapatan:</td>
            <td>Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
        </tr>
    </tbody>
</table>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikPenjualan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($label_tgl); ?>,
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: <?= json_encode($data_total); ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.7)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php include 'footer.php'; ?>