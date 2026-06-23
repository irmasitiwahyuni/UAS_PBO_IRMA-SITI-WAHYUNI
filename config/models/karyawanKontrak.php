<?php
// models/KaryawanKontrak.php
// SUBCLASS KARYAWAN KONTRAK - MEWARISI Karyawan

require_once 'Karyawan.php';

class KaryawanKontrak extends Karyawan {
    // ============================================
    // 1. PROPERTI TAMBAHAN (PRIVATE)
    // ============================================
    private $durasiKontrakBulan;
    private $agensiPenyalur;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $durasiKontrakBulan, $agensiPenyalur) {
        // Panggil konstruktor parent (Karyawan)
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        
        // Inisialisasi properti tambahan
        $this->durasiKontrakBulan = $durasiKontrakBulan;
        $this->agensiPenyalur = $agensiPenyalur;
    }

    // ============================================
    // 3. IMPLEMENTASI METHOD ABSTRAK (WAJIB)
    // ============================================
    
    /**
     * Menghitung gaji bersih untuk karyawan kontrak
     * Gaji = (gaji/hari * hari kerja) + bonus 10%
     */
    public function hitungGajiBersih() {
        $gajiKotor = $this->gajiDasarPerHari * $this->hariKerjaMasuk;
        $bonus = $gajiKotor * 0.1; // Bonus 10% untuk kontrak
        return $gajiKotor + $bonus;
    }

    /**
     * Menampilkan profil lengkap karyawan kontrak
     */
    public function tampilkanProfilKaryawan() {
        echo "========================================<br>";
        echo "📋 PROFIL KARYAWAN KONTRAK<br>";
        echo "========================================<br>";
        echo "ID Karyawan        : " . $this->id_karyawan . "<br>";
        echo "Nama Karyawan      : " . $this->nama_karyawan . "<br>";
        echo "Departemen         : " . $this->departemen . "<br>";
        echo "Hari Kerja Masuk   : " . $this->hariKerjaMasuk . " hari<br>";
        echo "Gaji Dasar/Hari    : Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "<br>";
        echo "----------------------------------------<br>";
        echo "📌 PROPERTI TAMBAHAN:<br>";
        echo "Durasi Kontrak     : " . $this->durasiKontrakBulan . " bulan<br>";
        echo "Agensi Penyalur    : " . $this->agensiPenyalur . "<br>";
        echo "----------------------------------------<br>";
        echo "💰 TOTAL GAJI BERSIH: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "<br>";
        echo "========================================<br><br>";
    }
}
?>