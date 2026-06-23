<?php
// models/KaryawanTetap.php
// SUBCLASS KARYAWAN TETAP

require_once 'Karyawan.php';

class KaryawanTetap extends Karyawan {
    // ============================================
    // 1. PROPERTI TAMBAHAN (PUBLIC)
    // ============================================
    public $tunjanganKesehatan;
    public $opsiSahamId;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $tunjanganKesehatan, $opsiSahamId) {
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        $this->tunjanganKesehatan = $tunjanganKesehatan;
        $this->opsiSahamId = $opsiSahamId;
    }

    // ============================================
    // 3. METHOD OVERRIDING - hitungGajiBersih()
    // ============================================
    public function hitungGajiBersih() {
        // Gaji Tetap = (hariKerjaMasuk * gajiDasarPerHari) + tunjanganKesehatan
        $gajiPokok = $this->hariKerjaMasuk * $this->gajiDasarPerHari;
        return $gajiPokok + $this->tunjanganKesehatan;
    }

    // ============================================
    // 4. METHOD OVERRIDING - tampilkanProfilKaryawan()
    // ============================================
    public function tampilkanProfilKaryawan() {
        echo "<div class='profil-card tetap'>";
        echo "<h3>🏢 Karyawan Tetap</h3>";
        echo "<p><strong>ID:</strong> " . $this->id_karyawan . "</p>";
        echo "<p><strong>Nama:</strong> " . $this->nama_karyawan . "</p>";
        echo "<p><strong>Departemen:</strong> " . $this->departemen . "</p>";
        echo "<p><strong>Hari Kerja:</strong> " . $this->hariKerjaMasuk . " hari</p>";
        echo "<p><strong>Gaji/Hari:</strong> Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "</p>";
        echo "<p><strong>Tunjangan Kesehatan:</strong> Rp " . number_format($this->tunjanganKesehatan, 0, ',', '.') . "</p>";
        echo "<p><strong>Opsi Saham ID:</strong> " . $this->opsiSahamId . "</p>";
        echo "<p class='total-gaji'><strong>Total Gaji:</strong> Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</p>";
        echo "</div>";
    }
}
?>