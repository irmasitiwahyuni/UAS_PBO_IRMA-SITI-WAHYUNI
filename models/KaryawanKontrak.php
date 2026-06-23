<?php
// models/KaryawanKontrak.php
// SUBCLASS KARYAWAN KONTRAK

require_once 'Karyawan.php';

class KaryawanKontrak extends Karyawan {
    // ============================================
    // 1. PROPERTI TAMBAHAN (PUBLIC)
    // ============================================
    public $durasiKontrakBulan;
    public $agensiPenyalur;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $durasiKontrakBulan, $agensiPenyalur) {
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        $this->durasiKontrakBulan = $durasiKontrakBulan;
        $this->agensiPenyalur = $agensiPenyalur;
    }

    // ============================================
    // 3. METHOD OVERRIDING - hitungGajiBersih()
    // ============================================
    public function hitungGajiBersih() {
        // Gaji Kontrak = hariKerjaMasuk * gajiDasarPerHari
        return $this->hariKerjaMasuk * $this->gajiDasarPerHari;
    }

    // ============================================
    // 4. METHOD OVERRIDING - tampilkanProfilKaryawan()
    // ============================================
    public function tampilkanProfilKaryawan() {
        echo "<div class='profil-card kontrak'>";
        echo "<h3>📋 Karyawan Kontrak</h3>";
        echo "<p><strong>ID:</strong> " . $this->id_karyawan . "</p>";
        echo "<p><strong>Nama:</strong> " . $this->nama_karyawan . "</p>";
        echo "<p><strong>Departemen:</strong> " . $this->departemen . "</p>";
        echo "<p><strong>Hari Kerja:</strong> " . $this->hariKerjaMasuk . " hari</p>";
        echo "<p><strong>Gaji/Hari:</strong> Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "</p>";
        echo "<p><strong>Durasi Kontrak:</strong> " . $this->durasiKontrakBulan . " bulan</p>";
        echo "<p><strong>Agensi Penyalur:</strong> " . $this->agensiPenyalur . "</p>";
        echo "<p class='total-gaji'><strong>Total Gaji:</strong> Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</p>";
        echo "</div>";
    }
}
?>