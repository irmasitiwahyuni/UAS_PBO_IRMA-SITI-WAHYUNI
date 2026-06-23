<?php
// models/KaryawanMagang.php
// SUBCLASS KARYAWAN MAGANG

require_once 'Karyawan.php';

class KaryawanMagang extends Karyawan {
    // ============================================
    // 1. PROPERTI TAMBAHAN (PUBLIC)
    // ============================================
    public $uangSakuBulanan;
    public $sertifikatKampusMerdeka;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $uangSakuBulanan, $sertifikatKampusMerdeka) {
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        $this->uangSakuBulanan = $uangSakuBulanan;
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }

    // ============================================
    // 3. METHOD OVERRIDING - hitungGajiBersih()
    // ============================================
    public function hitungGajiBersih() {
        // Gaji Magang = (hariKerjaMasuk * gajiDasarPerHari) * 0.80
        $gajiKotor = $this->hariKerjaMasuk * $this->gajiDasarPerHari;
        return $gajiKotor * 0.80; // Potongan 20%
    }

    // ============================================
    // 4. METHOD OVERRIDING - tampilkanProfilKaryawan()
    // ============================================
    public function tampilkanProfilKaryawan() {
        $gajiKotor = $this->hariKerjaMasuk * $this->gajiDasarPerHari;
        $potongan = $gajiKotor * 0.20;
        
        echo "<div class='profil-card magang'>";
        echo "<h3>🎓 Karyawan Magang</h3>";
        echo "<p><strong>ID:</strong> " . $this->id_karyawan . "</p>";
        echo "<p><strong>Nama:</strong> " . $this->nama_karyawan . "</p>";
        echo "<p><strong>Departemen:</strong> " . $this->departemen . "</p>";
        echo "<p><strong>Hari Kerja:</strong> " . $this->hariKerjaMasuk . " hari</p>";
        echo "<p><strong>Gaji/Hari:</strong> Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "</p>";
        echo "<p><strong>Uang Saku Bulanan:</strong> Rp " . number_format($this->uangSakuBulanan, 0, ',', '.') . "</p>";
        echo "<p><strong>Sertifikat MSIB:</strong> " . $this->sertifikatKampusMerdeka . "</p>";
        echo "<p><strong>Gaji Kotor:</strong> Rp " . number_format($gajiKotor, 0, ',', '.') . "</p>";
        echo "<p class='potongan'><strong>Potongan 20%:</strong> - Rp " . number_format($potongan, 0, ',', '.') . "</p>";
        echo "<p class='total-gaji'><strong>Total Gaji Bersih:</strong> Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</p>";
        echo "</div>";
    }
}
?>