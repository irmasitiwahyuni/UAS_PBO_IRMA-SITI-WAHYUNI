<?php
// index.php
// MAIN PROGRAM - SISTEM MANAJEMEN KARYAWAN

require_once 'config/koneksi.php';
require_once 'models/Karyawan.php';
require_once 'models/KaryawanKontrak.php';
require_once 'models/KaryawanTetap.php';
require_once 'models/KaryawanMagang.php';

global $koneksi;

// ============================================
// 1. AMBIL DATA DARI DATABASE
// ============================================
$sql = "SELECT * FROM tabel_karyawan ORDER BY jenis_karyawan, id_karyawan";
$result = $koneksi->query($sql);

$dataKontrak = [];
$dataTetap = [];
$dataMagang = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jenis = $row['jenis_karyawan'];
        
        switch ($jenis) {
            case 'Kontrak':
                $karyawan = new KaryawanKontrak(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['durasi_kontrak_bulan'],
                    $row['agensi_penyalur']
                );
                $dataKontrak[] = $karyawan;
                break;
                
            case 'Tetap':
                $karyawan = new KaryawanTetap(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['tunjangan_kesehatan'],
                    $row['opsi_saham_id']
                );
                $dataTetap[] = $karyawan;
                break;
                
            case 'Magang':
                $karyawan = new KaryawanMagang(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['uang_saku_bulanan'],
                    $row['sertifikat_kampus_merdeka']
                );
                $dataMagang[] = $karyawan;
                break;
        }
    }
}

// ============================================
// 2. HITUNG STATISTIK
// ============================================
$totalKontrak = count($dataKontrak);
$totalTetap = count($dataTetap);
$totalMagang = count($dataMagang);
$totalSemua = $totalKontrak + $totalTetap + $totalMagang;

$totalGajiKontrak = 0;
$totalGajiTetap = 0;
$totalGajiMagang = 0;

foreach ($dataKontrak as $k) { $totalGajiKontrak += $k->hitungGajiBersih(); }
foreach ($dataTetap as $k) { $totalGajiTetap += $k->hitungGajiBersih(); }
foreach ($dataMagang as $k) { $totalGajiMagang += $k->hitungGajiBersih(); }

$totalGajiSemua = $totalGajiKontrak + $totalGajiTetap + $totalGajiMagang;

// ============================================
// 3. DETEKSI TAB AKTIF
// ============================================
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Karyawan - UAS PBO</title>
    <style>
        /* ========== CSS GLOBAL ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ========== HEADER ========== */
        .header {
            background: linear-gradient(135deg, #1a237e, #0d47a1);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-left h1 {
            font-size: 24px;
        }

        .header-left h1 span {
            color: #ffd54f;
        }

        .header-left .subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .header-left .subtitle-small {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 3px;
        }

        .nav-tabs {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .nav-tabs a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s;
            background: rgba(255,255,255,0.1);
            font-size: 14px;
        }

        .nav-tabs a:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        .nav-tabs a.active {
            background: #ffd54f;
            color: #1a237e;
            font-weight: bold;
        }

        .nav-tabs a .badge-nav {
            background: rgba(255,0,0,0.8);
            color: white;
            border-radius: 50%;
            padding: 1px 7px;
            font-size: 10px;
            margin-left: 5px;
        }

        /* ========== STATISTIK CARD ========== */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            border-bottom: 4px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }

        .stat-card .label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .stat-card .icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .stat-card .total-gaji {
            font-size: 12px;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 20px;
            background: #f5f5f5;
            display: inline-block;
        }

        .stat-card.kontrak { border-bottom-color: #e65100; }
        .stat-card.kontrak .number { color: #e65100; }
        .stat-card.tetap { border-bottom-color: #1a237e; }
        .stat-card.tetap .number { color: #1a237e; }
        .stat-card.magang { border-bottom-color: #2e7d32; }
        .stat-card.magang .number { color: #2e7d32; }
        .stat-card.total { border-bottom-color: #c62828; }
        .stat-card.total .number { color: #c62828; }

        /* ========== KATEGORI SECTION ========== */
        .kategori-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .kategori-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .kategori-header:hover {
            opacity: 0.9;
        }

        .kategori-header.kontrak {
            background: #fff3e0;
            border-left: 5px solid #e65100;
        }

        .kategori-header.tetap {
            background: #e3f2fd;
            border-left: 5px solid #1a237e;
        }

        .kategori-header.magang {
            background: #e8f5e9;
            border-left: 5px solid #2e7d32;
        }

        .kategori-header h2 {
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kategori-header .badge {
            background: rgba(0,0,0,0.1);
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: normal;
        }

        .kategori-body {
            padding: 20px;
            overflow-x: auto;
        }

        /* ========== TABEL ========== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table thead {
            background: #f5f5f5;
        }

        table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
            white-space: nowrap;
        }

        table td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .gaji-total {
            font-weight: bold;
            color: #1a237e;
        }

        .gaji-potongan {
            color: #c62828;
            font-size: 12px;
        }

        .table-footer {
            background: #fafafa;
            padding: 10px 15px;
            border-radius: 5px;
            display: flex;
            justify-content: flex-end;
            gap: 30px;
            font-weight: bold;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .table-footer .label {
            color: #666;
            font-weight: normal;
        }

        /* ========== GRAND TOTAL ========== */
        .grand-total {
            background: linear-gradient(135deg, #1a237e, #0d47a1);
            color: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .grand-total h3 {
            color: white;
            font-size: 18px;
        }

        .grand-total .detail {
            opacity: 0.85;
            font-size: 14px;
        }

        .grand-total .total-nominal {
            font-size: 26px;
            font-weight: bold;
        }

        /* ========== TOMBOL ========== */
        .btn-print {
            padding: 10px 25px;
            background: #1a237e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-print:hover {
            background: #0d47a1;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-back {
            background: #f5f5f5;
            color: #333;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #e0e0e0;
            transform: translateX(-3px);
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state .empty-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 5px;
        }

        /* ========== FOOTER ========== */
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #ddd;
            margin-top: 30px;
        }

        .footer .footer-dev {
            font-size: 12px;
            color: #999;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .nav-tabs {
                justify-content: center;
            }

            .nav-tabs a {
                font-size: 12px;
                padding: 6px 12px;
            }

            table {
                font-size: 12px;
            }

            table th,
            table td {
                padding: 6px 8px;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .kategori-header {
                flex-direction: column;
                text-align: center;
            }

            .grand-total {
                flex-direction: column;
                text-align: center;
            }

            .table-footer {
                justify-content: center;
            }
        }

        /* ========== PRINT STYLE ========== */
        @media print {
            .header {
                background: #1a237e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .nav-tabs {
                display: none !important;
            }

            .stats-container {
                display: none !important;
            }

            .btn-print {
                display: none !important;
            }

            .btn-back {
                display: none !important;
            }

            .kategori-section {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .grand-total {
                background: #1a237e !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                padding: 10px;
                background: white;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ============================================ -->
        <!-- HEADER -->
        <!-- ============================================ -->
        <div class="header">
            <div class="header-left">
                <h1>🏢 <span>Sistem Manajemen Karyawan</span></h1>
                <div class="subtitle">UAS Pemrograman Berorientasi Objek - TRPL 1B</div>
                <div class="subtitle-small">Nama: Irma Siti Wahyuni</div>
            </div>
            <div class="nav-tabs">
                <a href="?tab=dashboard" class="<?= $tab == 'dashboard' ? 'active' : '' ?>">
                    🏠 Dashboard
                </a>
                <a href="?tab=kontrak" class="<?= $tab == 'kontrak' ? 'active' : '' ?>">
                    📄 Kontrak <span class="badge-nav"><?= $totalKontrak ?></span>
                </a>
                <a href="?tab=tetap" class="<?= $tab == 'tetap' ? 'active' : '' ?>">
                    🏢 Tetap <span class="badge-nav"><?= $totalTetap ?></span>
                </a>
                <a href="?tab=magang" class="<?= $tab == 'magang' ? 'active' : '' ?>">
                    🎓 Magang <span class="badge-nav"><?= $totalMagang ?></span>
                </a>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- STATISTIK CARD -->
        <!-- ============================================ -->
        <div class="stats-container">
            <div class="stat-card kontrak" onclick="window.location.href='?tab=kontrak'">
                <div class="icon">📋</div>
                <div class="number"><?= $totalKontrak ?></div>
                <div class="label">Karyawan Kontrak</div>
                <div class="total-gaji">
                    Rp <?= number_format($totalGajiKontrak, 0, ',', '.') ?>
                </div>
            </div>
            <div class="stat-card tetap" onclick="window.location.href='?tab=tetap'">
                <div class="icon">🏢</div>
                <div class="number"><?= $totalTetap ?></div>
                <div class="label">Karyawan Tetap</div>
                <div class="total-gaji">
                    Rp <?= number_format($totalGajiTetap, 0, ',', '.') ?>
                </div>
            </div>
            <div class="stat-card magang" onclick="window.location.href='?tab=magang'">
                <div class="icon">🎓</div>
                <div class="number"><?= $totalMagang ?></div>
                <div class="label">Karyawan Magang</div>
                <div class="total-gaji">
                    Rp <?= number_format($totalGajiMagang, 0, ',', '.') ?>
                </div>
            </div>
            <div class="stat-card total">
                <div class="icon">👥</div>
                <div class="number"><?= $totalSemua ?></div>
                <div class="label">Total Karyawan</div>
                <div class="total-gaji">
                    Rp <?= number_format($totalGajiSemua, 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- KONTEN BERDASARKAN TAB -->
        <!-- ============================================ -->

        <?php if ($tab == 'dashboard'): ?>
            <!-- ===== DASHBOARD ===== -->
            
            <!-- KONTRAK -->
            <div class="kategori-section">
                <div class="kategori-header kontrak" onclick="window.location.href='?tab=kontrak'">
                    <h2>
                        📋 Karyawan Kontrak
                        <span class="badge"><?= $totalKontrak ?> orang</span>
                    </h2>
                    <div class="total-kategori" style="color:#e65100;">
                        Total Gaji: Rp <?= number_format($totalGajiKontrak, 0, ',', '.') ?>
                    </div>
                </div>
                <div class="kategori-body">
                    <?php if (count($dataKontrak) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th class="text-right">Hari Kerja</th>
                                    <th class="text-right">Gaji/Hari</th>
                                    <th>Durasi</th>
                                    <th class="text-right">Total Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataKontrak as $k): ?>
                                    <tr>
                                        <td><strong><?= $k->id_karyawan ?></strong></td>
                                        <td><?= $k->nama_karyawan ?></td>
                                        <td><?= $k->departemen ?></td>
                                        <td class="text-right"><?= $k->hariKerjaMasuk ?> hari</td>
                                        <td class="text-right">Rp <?= number_format($k->gajiDasarPerHari, 0, ',', '.') ?></td>
                                        <td><?= $k->durasiKontrakBulan ?> bln</td>
                                        <td class="text-right gaji-total">
                                            Rp <?= number_format($k->hitungGajiBersih(), 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="table-footer">
                            <span><span class="label">SUB TOTAL KONTRAK:</span> Rp <?= number_format($totalGajiKontrak, 0, ',', '.') ?></span>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada karyawan kontrak yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TETAP -->
            <div class="kategori-section">
                <div class="kategori-header tetap" onclick="window.location.href='?tab=tetap'">
                    <h2>
                        🏢 Karyawan Tetap
                        <span class="badge"><?= $totalTetap ?> orang</span>
                    </h2>
                    <div class="total-kategori" style="color:#1a237e;">
                        Total Gaji: Rp <?= number_format($totalGajiTetap, 0, ',', '.') ?>
                    </div>
                </div>
                <div class="kategori-body">
                    <?php if (count($dataTetap) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th class="text-right">Hari Kerja</th>
                                    <th class="text-right">Gaji/Hari</th>
                                    <th class="text-right">Tunjangan</th>
                                    <th class="text-right">Total Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTetap as $k): ?>
                                    <tr>
                                        <td><strong><?= $k->id_karyawan ?></strong></td>
                                        <td><?= $k->nama_karyawan ?></td>
                                        <td><?= $k->departemen ?></td>
                                        <td class="text-right"><?= $k->hariKerjaMasuk ?> hari</td>
                                        <td class="text-right">Rp <?= number_format($k->gajiDasarPerHari, 0, ',', '.') ?></td>
                                        <td class="text-right">Rp <?= number_format($k->tunjanganKesehatan, 0, ',', '.') ?></td>
                                        <td class="text-right gaji-total">
                                            Rp <?= number_format($k->hitungGajiBersih(), 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="table-footer">
                            <span><span class="label">SUB TOTAL TETAP:</span> Rp <?= number_format($totalGajiTetap, 0, ',', '.') ?></span>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada karyawan tetap yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MAGANG -->
            <div class="kategori-section">
                <div class="kategori-header magang" onclick="window.location.href='?tab=magang'">
                    <h2>
                        🎓 Karyawan Magang
                        <span class="badge"><?= $totalMagang ?> orang</span>
                    </h2>
                    <div class="total-kategori" style="color:#2e7d32;">
                        Total Gaji: Rp <?= number_format($totalGajiMagang, 0, ',', '.') ?>
                    </div>
                </div>
                <div class="kategori-body">
                    <?php if (count($dataMagang) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Departemen</th>
                                    <th class="text-right">Hari Kerja</th>
                                    <th class="text-right">Gaji/Hari</th>
                                    <th class="text-right">Gaji Kotor</th>
                                    <th class="text-right">Potongan 20%</th>
                                    <th class="text-right">Gaji Bersih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataMagang as $k): 
                                    $gajiKotor = $k->hariKerjaMasuk * $k->gajiDasarPerHari;
                                    $potongan = $gajiKotor * 0.20;
                                ?>
                                    <tr>
                                        <td><strong><?= $k->id_karyawan ?></strong></td>
                                        <td><?= $k->nama_karyawan ?></td>
                                        <td><?= $k->departemen ?></td>
                                        <td class="text-right"><?= $k->hariKerjaMasuk ?> hari</td>
                                        <td class="text-right">Rp <?= number_format($k->gajiDasarPerHari, 0, ',', '.') ?></td>
                                        <td class="text-right">Rp <?= number_format($gajiKotor, 0, ',', '.') ?></td>
                                        <td class="text-right gaji-potongan">
                                            - Rp <?= number_format($potongan, 0, ',', '.') ?>
                                            <br><small>(20%)</small>
                                        </td>
                                        <td class="text-right gaji-total">
                                            Rp <?= number_format($k->hitungGajiBersih(), 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="table-footer">
                            <span><span class="label">SUB TOTAL MAGANG:</span> Rp <?= number_format($totalGajiMagang, 0, ',', '.') ?></span>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada karyawan magang yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($tab == 'kontrak'): ?>
            <!-- ===== HALAMAN KHUSUS KONTRAK ===== -->
            <div style="margin-bottom: 20px;">
                <a href="?tab=dashboard" class="btn-back">⬅ Kembali ke Dashboard</a>
            </div>
            
            <div class="kategori-section">
                <div class="kategori-header kontrak">
                    <h2>
                        📋 Daftar Karyawan Kontrak
                        <span class="badge"><?= $totalKontrak ?> orang</span>
                    </h2>
                    <div class="total-kategori" style="color:#e65100;">
                        Total Gaji: Rp <?= number_format($totalGajiKontrak, 0, ',', '.') ?>
                    </div>
                </div>
                <div class="kategori-body">
                    <?php if (count($dataKontrak) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Karyawan</th>
                                    <th>Departemen</th>
                                    <th class="text-right">Hari Kerja</th>
                                    <th class="text-right">Gaji/Hari</th>
                                    <th>Durasi Kontrak</th>
                                    <th>Agensi Penyalur</th>
                                    <th class="text-right">Total Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataKontrak as $k): ?>
                                    <tr>
                                        <td><strong><?= $k->id_karyawan ?></strong></td>
                                        <td><?= $k->nama_karyawan ?></td>
                                        <td><?= $k->departemen ?></td>
                                        <td class="text-right"><?= $k->hariKerjaMasuk ?> hari</td>
                                        <td class="text-right">Rp <?= number_format($k->gajiDasarPerHari, 0, ',', '.') ?></td>
                                        <td><?= $k->durasiKontrakBulan ?> bulan</td>
                                        <td><?= $k->agensiPenyalur ?></td>
                                        <td class="text-right gaji-total">
                                            Rp <?= number_format($k->hitungGajiBersih(), 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background: #fff3e0; font-weight:bold;">
                                    <td colspan="7" class="text-right">TOTAL KONTRAK</td>
                                    <td class="text-right">Rp <?= number_format($totalGajiKontrak, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada karyawan kontrak yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($tab == 'tetap'): ?>
            <!-- ===== HALAMAN KHUSUS TETAP ===== -->
            <div style="margin-bottom: 20px;">
                <a href="?tab=dashboard" class="btn-back">⬅ Kembali ke Dashboard</a>
            </div>
            
            <div class="kategori-section">
                <div class="kategori-header tetap">
                    <h2>
                        🏢 Daftar Karyawan Tetap
                        <span class="badge"><?= $totalTetap ?> orang</span>
                    </h2>
                    <div class="total-kategori" style="color:#1a237e;">
                        Total Gaji: Rp <?= number_format($totalGajiTetap, 0, ',', '.') ?>
                    </div>
                </div>
                <div class="kategori-body">
                    <?php if (count($dataTetap) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Karyawan</th>
                                    <th>Departemen</th>
                                    <th class="text-right">Hari Kerja</th>
                                    <th class="text-right">Gaji/Hari</th>
                                    <th class="text-right">Tunjangan Kesehatan</th>
                                    <th>Opsi Saham ID</th>
                                    <th class="text-right">Total Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataTetap as $k): ?>
                                    <tr>
                                        <td><strong><?= $k->id_karyawan ?></strong></td>
                                        <td><?= $k->nama_karyawan ?></td>
                                        <td><?= $k->departemen ?></td>
                                        <td class="text-right"><?= $k->hariKerjaMasuk ?> hari</td>
                                        <td class="text-right">Rp <?= number_format($k->gajiDasarPerHari, 0, ',', '.') ?></td>
                                        <td class="text-right">Rp <?= number_format($k->tunjanganKesehatan, 0, ',', '.') ?></td>
                                        <td><?= $k->opsiSahamId ?></td>
                                        <td class="text-right gaji-total">
                                            Rp <?= number_format($k->hitungGajiBersih(), 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background: #e3f2fd; font-weight:bold;">
                                    <td colspan="7" class="text-right">TOTAL TETAP</td>
                                    <td class="text-right">Rp <?= number_format($totalGajiTetap, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada karyawan tetap yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($tab == 'magang'): ?>
            <!-- ===== HALAMAN KHUSUS MAGANG ===== -->
            <div style="margin-bottom: 20px;">
                <a href="?tab=dashboard" class="btn-back">⬅ Kembali ke Dashboard</a>
            </div>
            
            <div class="kategori-section">
                <div class="kategori-header magang">
                    <h2>
                        🎓 Daftar Karyawan Magang
                        <span class="badge"><?= $totalMagang ?> orang</span>
                    </h2>
                    <div class="total-kategori" style="color:#2e7d32;">
                        Total Gaji: Rp <?= number_format($totalGajiMagang, 0, ',', '.') ?>
                    </div>
                </div>
                <div class="kategori-body">
                    <?php if (count($dataMagang) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Karyawan</th>
                                    <th>Departemen</th>
                                    <th class="text-right">Hari Kerja</th>
                                    <th class="text-right">Gaji/Hari</th>
                                    <th class="text-right">Gaji Kotor</th>
                                    <th class="text-right">Potongan 20%</th>
                                    <th class="text-right">Gaji Bersih</th>
                                    <th>Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataMagang as $k): 
                                    $gajiKotor = $k->hariKerjaMasuk * $k->gajiDasarPerHari;
                                    $potongan = $gajiKotor * 0.20;
                                ?>
                                    <tr>
                                        <td><strong><?= $k->id_karyawan ?></strong></td>
                                        <td><?= $k->nama_karyawan ?></td>
                                        <td><?= $k->departemen ?></td>
                                        <td class="text-right"><?= $k->hariKerjaMasuk ?> hari</td>
                                        <td class="text-right">Rp <?= number_format($k->gajiDasarPerHari, 0, ',', '.') ?></td>
                                        <td class="text-right">Rp <?= number_format($gajiKotor, 0, ',', '.') ?></td>
                                        <td class="text-right gaji-potongan">
                                            - Rp <?= number_format($potongan, 0, ',', '.') ?>
                                            <br><small>(20%)</small>
                                        </td>
                                        <td class="text-right gaji-total">
                                            Rp <?= number_format($k->hitungGajiBersih(), 0, ',', '.') ?>
                                        </td>
                                        <td><?= $k->sertifikatKampusMerdeka ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr style="background: #e8f5e9; font-weight:bold;">
                                    <td colspan="8" class="text-right">TOTAL MAGANG</td>
                                    <td class="text-right">Rp <?= number_format($totalGajiMagang, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada karyawan magang yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>

        <!-- ============================================ -->
        <!-- GRAND TOTAL -->
        <!-- ============================================ -->
        <div class="grand-total">
            <div>
                <h3>📊 GRAND TOTAL SEMUA KARYAWAN</h3>
                <div class="detail">
                    <?= $totalSemua ?> Karyawan | 
                    Kontrak: <?= $totalKontrak ?> | 
                    Tetap: <?= $totalTetap ?> | 
                    Magang: <?= $totalMagang ?>
                </div>
            </div>
            <div class="total-nominal">
                Rp <?= number_format($totalGajiSemua, 0, ',', '.') ?>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- TOMBOL AKSI -->
        <!-- ============================================ -->
        <div style="text-align: right; margin-top: 20px; display: flex; gap: 15px; justify-content: flex-end; flex-wrap: wrap;">
            <button onclick="window.print()" class="btn-print">
                🖨️ Cetak Slip Gaji
            </button>
            <a href="?tab=dashboard" class="btn-print" style="background:#2e7d32; text-decoration:none;">
                🏠 Dashboard
            </a>
        </div>

        <!-- ============================================ -->
        <!-- FOOTER -->
        <!-- ============================================ -->
        <div class="footer">
            <p>&copy; <?= date('Y') ?> - Sistem Manajemen Karyawan | UAS PBO - TRPL 1B</p>
            <p class="footer-dev">Dibangun dengan OOP Murni PHP &amp; MySQL | Nama: Irma Siti Wahyuni</p>
        </div>
    </div>

    <script>
        // Fitur toggle collapsible
        document.addEventListener('DOMContentLoaded', function() {
            const headers = document.querySelectorAll('.kategori-header');
            headers.forEach(header => {
                header.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A') return;
                    const body = this.nextElementSibling;
                    if (body && body.classList.contains('kategori-body')) {
                        if (body.style.display === 'none') {
                            body.style.display = 'block';
                        } else {
                            body.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>