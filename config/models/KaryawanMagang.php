<?php
// models/KaryawanMagang.php
// SUBCLASS KARYAWAN MAGANG - MEWARISI Karyawan

require_once 'Karyawan.php';

class KaryawanMagang extends Karyawan {
    // ============================================
    // 1. PROPERTI TAMBAHAN (PRIVATE)
    // ============================================
    private $uangSakuBulanan;
    private $sertifikatKampusMerdeka;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $uangSakuBulanan, $sertifikatKampusMerdeka) {
        // Panggil konstruktor parent (Karyawan)
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        
        // Inisialisasi properti tambahan
        $this->uangSakuBulanan = $uangSakuBulanan;
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }

    // ============================================
    // 3. IMPLEMENTASI METHOD ABSTRAK (WAJIB)
    // ============================================
    
    /**
     * Menghitung gaji bersih untuk karyawan magang
     * Magang = uang saku bulanan (bukan harian)
     */
    public function hitungGajiBersih() {
        return $this->uangSakuBulanan;
    }

    /**
     * Menampilkan profil lengkap karyawan magang
     */
    public function tampilkanProfilKaryawan() {
        echo "========================================<br>";
        echo "🎓 PROFIL KARYAWAN MAGANG<br>";
        echo "========================================<br>";
        echo "ID Karyawan        : " . $this->id_karyawan . "<br>";
        echo "Nama Karyawan      : " . $this->nama_karyawan . "<br>";
        echo "Departemen         : " . $this->departemen . "<br>";
        echo "Hari Kerja Masuk   : " . $this->hariKerjaMasuk . " hari<br>";
        echo "Gaji Dasar/Hari    : Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "<br>";
        echo "----------------------------------------<br>";
        echo "📌 PROPERTI TAMBAHAN:<br>";
        echo "Uang Saku Bulanan  : Rp " . number_format($this->uangSakuBulanan, 0, ',', '.') . "<br>";
        echo "Sertifikat MSIB    : " . $this->sertifikatKampusMerdeka . "<br>";
        echo "----------------------------------------<br>";
        echo "💰 TOTAL GAJI BERSIH: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "<br>";
        echo "========================================<br><br>";
    }
}
?>