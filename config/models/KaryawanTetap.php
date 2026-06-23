<?php
// models/KaryawanTetap.php
// SUBCLASS KARYAWAN TETAP - MEWARISI Karyawan

require_once 'Karyawan.php';

class KaryawanTetap extends Karyawan {
    // ============================================
    // 1. PROPERTI TAMBAHAN (PRIVATE)
    // ============================================
    private $tunjanganKesehatan;
    private $opsiSahamId;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari,
                                $tunjanganKesehatan, $opsiSahamId) {
        // Panggil konstruktor parent (Karyawan)
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        
        // Inisialisasi properti tambahan
        $this->tunjanganKesehatan = $tunjanganKesehatan;
        $this->opsiSahamId = $opsiSahamId;
    }

    // ============================================
    // 3. IMPLEMENTASI METHOD ABSTRAK (WAJIB)
    // ============================================
    
    /**
     * Menghitung gaji bersih untuk karyawan tetap
     * Gaji = (gaji/hari * hari kerja) + tunjangan kesehatan
     */
    public function hitungGajiBersih() {
        $gajiKotor = $this->gajiDasarPerHari * $this->hariKerjaMasuk;
        return $gajiKotor + $this->tunjanganKesehatan;
    }

    /**
     * Menampilkan profil lengkap karyawan tetap
     */
    public function tampilkanProfilKaryawan() {
        echo "========================================<br>";
        echo "🏢 PROFIL KARYAWAN TETAP<br>";
        echo "========================================<br>";
        echo "ID Karyawan        : " . $this->id_karyawan . "<br>";
        echo "Nama Karyawan      : " . $this->nama_karyawan . "<br>";
        echo "Departemen         : " . $this->departemen . "<br>";
        echo "Hari Kerja Masuk   : " . $this->hariKerjaMasuk . " hari<br>";
        echo "Gaji Dasar/Hari    : Rp " . number_format($this->gajiDasarPerHari, 0, ',', '.') . "<br>";
        echo "----------------------------------------<br>";
        echo "📌 PROPERTI TAMBAHAN:<br>";
        echo "Tunjangan Kesehatan: Rp " . number_format($this->tunjanganKesehatan, 0, ',', '.') . "<br>";
        echo "Opsi Saham ID      : " . $this->opsiSahamId . "<br>";
        echo "----------------------------------------<br>";
        echo "💰 TOTAL GAJI BERSIH: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "<br>";
        echo "========================================<br><br>";
    }
}
?>