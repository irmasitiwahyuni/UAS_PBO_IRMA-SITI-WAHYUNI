<?php
// models/KaryawanKontrak.php
// CLASS ANAK - MENGIMPLEMENTASI ABSTRACT CLASS Karyawan

require_once 'Karyawan.php';

class KaryawanKontrak extends Karyawan {
    // ============================================
    // 1. PROPERTI SPESIFIK
    // ============================================
    private $durasi_kontrak_bulan;
    private $agensi_penyalur;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $durasi_kontrak_bulan, $agensi_penyalur) {
        // Panggil konstruktor parent
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        $this->durasi_kontrak_bulan = $durasi_kontrak_bulan;
        $this->agensi_penyalur = $agensi_penyalur;
    }

    // ============================================
    // 3. IMPLEMENTASI METHOD ABSTRAK (WAJIB)
    // ============================================
    
    // Implementasi method hitungGajiBersih()
    public function hitungGajiBersih() {
        // Gaji kontrak = (gaji/hari * hari kerja) + bonus 10%
        $gajiKotor = $this->gajiDasarPerHari * $this->hariKerjaMasuk;
        $bonus = $gajiKotor * 0.1;
        return $gajiKotor + $bonus;
    }

    // Implementasi method tampilkanProfilKaryawan()
    public function tampilkanProfilKaryawan() {
        echo "========================================<br>";
        echo "📋 PROFIL KARYAWAN KONTRAK<br>";
        echo "========================================<br>";
        echo "ID Karyawan        : " . $this->id_karyawan . "<br>";
        echo "Nama Karyawan      : " . $this->nama_karyawan . "<br>";
        echo "Departemen         : " . $this->departemen . "<br>";
        echo "Hari Kerja Masuk   : " . $this->hariKerjaMasuk . " hari<br>";
        echo "Gaji Dasar/Hari    : Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "<br>";
        echo "Durasi Kontrak     : " . $this->durasi_kontrak_bulan . " bulan<br>";
        echo "Agensi Penyalur    : " . $this->agensi_penyalur . "<br>";
        echo "----------------------------------------<br>";
        echo "TOTAL GAJI BERSIH  : Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "<br>";
        echo "========================================<br><br>";
    }
}
?>