<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-primary h-100">
            <div class="card-body">
                <h5 class="card-title text-primary">Saldo Saat Ini</h5>
                <p class="card-text fs-4 fw-bold">Rp 10.500.000</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-success h-100">
            <div class="card-body">
                <h5 class="card-title text-success">Pemasukan Bulan Ini</h5>
                <p class="card-text fs-4 fw-bold">Rp 15.000.000</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-danger h-100">
            <div class="card-body">
                <h5 class="card-title text-danger">Pengeluaran Bulan Ini</h5>
                <p class="card-text fs-4 fw-bold">Rp 4.500.000</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pengeluaran per Kategori</h5>
                <canvas id="expenseChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaksi Terakhir</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>Gaji Bulan September<br><small class="text-muted">Gaji</small></div>
                        <span class="fw-bold text-success">+ Rp 15.000.000</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>Bayar Tagihan Listrik<br><small class="text-muted">Kebutuhan Rumah</small></div>
                        <span class="fw-bold text-danger">- Rp 750.000</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>Belanja Bulanan<br><small class="text-muted">Belanja</small></div>
                        <span class="fw-bold text-danger">- Rp 1.200.000</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>Makan Siang<br><small class="text-muted">Makanan</small></div>
                        <span class="fw-bold text-danger">- Rp 50.000</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>Transportasi<br><small class="text-muted">Transportasi</small></div>
                        <span class="fw-bold text-danger">- Rp 300.000</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('expenseChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Kebutuhan Rumah', 'Belanja', 'Makanan', 'Transportasi', 'Hiburan'],
            datasets: [{
                label: 'Pengeluaran',
                data: [750000, 1200000, 850000, 900000, 800000],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
<?php
// Panggil footer
require 'layouts/footer.php';
?>