<?php
// models/Karyawan.php
// ABSTRACT CLASS KARYAWAN

abstract class Karyawan {
    // ============================================
    // 1. PROPERTI/ATRIBUT (PROTECTED - ENKAPSULASI)
    // ============================================
    protected $id_karyawan;
    protected $nama_karyawan;
    protected $departemen;
    protected $hariKerjaMasuk;
    protected $gajiDasarPerHari;

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
    
    /**
     * Method abstrak untuk menghitung gaji bersih
     * Wajib diimplementasikan oleh class anak
     */
    abstract public function hitungGajiBersih();
    
    /**
     * Method abstrak untuk menampilkan profil karyawan
     * Wajib diimplementasikan oleh class anak
     */
    abstract public function tampilkanProfilKaryawan();
}
?>