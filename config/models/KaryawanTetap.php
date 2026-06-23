<?php
// models/KaryawanTetap.php
// CLASS ANAK - MENGIMPLEMENTASI ABSTRACT CLASS Karyawan

require_once 'Karyawan.php';

class KaryawanTetap extends Karyawan {
    // ============================================
    // 1. PROPERTI SPESIFIK
    // ============================================
    private $tunjangan_kesehatan;
    private $opsi_saham_id;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $tunjangan_kesehatan, $opsi_saham_id) {
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        $this->tunjangan_kesehatan = $tunjangan_kesehatan;
        $this->opsi_saham_id = $opsi_saham_id;
    }

    // ============================================
    // 3. IMPLEMENTASI METHOD ABSTRAK (WAJIB)
    // ============================================
    
    public function hitungGajiBersih() {
        // Gaji tetap = (gaji/hari * hari kerja) + tunjangan kesehatan
        $gajiKotor = $this->gajiDasarPerHari * $this->hariKerjaMasuk;
        return $gajiKotor + $this->tunjangan_kesehatan;
    }

    public function tampilkanProfilKaryawan() {
        echo "========================================<br>";
        echo "🏢 PROFIL KARYAWAN TETAP<br>";
        echo "========================================<br>";
        echo "ID Karyawan        : " . $this->id_karyawan . "<br>";
        echo "Nama Karyawan      : " . $this->nama_karyawan . "<br>";
        echo "Departemen         : " . $this->departemen . "<br>";
        echo "Hari Kerja Masuk   : " . $this->hariKerjaMasuk . " hari<br>";
        echo "Gaji Dasar/Hari    : Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "<br>";
        echo "Tunjangan Kesehatan: Rp " . number_format($this->tunjangan_kesehatan, 0, ',', '.') . "<br>";
        echo "Opsi Saham ID      : " . $this->opsi_saham_id . "<br>";
        echo "----------------------------------------<br>";
        echo "TOTAL GAJI BERSIH  : Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "<br>";
        echo "========================================<br><br>";
    }
}
?>