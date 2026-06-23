<?php
// models/Karyawan.php
// ABSTRACT CLASS KARYAWAN (INDUK)

abstract class Karyawan {
    // ============================================
    // 1. PROPERTI (PUBLIC - AGAR BISA DIAKSES VIEW)
    // ============================================
    public $id_karyawan;
    public $nama_karyawan;
    public $departemen;
    public $hariKerjaMasuk;
    public $gajiDasarPerHari;

    // ============================================
    // 2. KONSTRUKTOR
    // ============================================
    public function __construct($id_karyawan, $nama_karyawan, $departemen, 
                                $hariKerjaMasuk, $gajiDasarPerHari) {
        $this->id_karyawan = $id_karyawan;
        $this->nama_karyawan = $nama_karyawan;
        $this->departemen = $departemen;
        $this->hariKerjaMasuk = $hariKerjaMasuk;
        $this->gajiDasarPerHari = $gajiDasarPerHari;
    }

    // ============================================
    // 3. METHOD ABSTRAK (WAJIB DIIMPLEMENTASI ANAK)
    // ============================================
    abstract public function hitungGajiBersih();
    abstract public function tampilkanProfilKaryawan();
}
?>