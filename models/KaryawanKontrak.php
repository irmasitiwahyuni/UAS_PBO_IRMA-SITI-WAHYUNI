<?php
// models/KaryawanKaryawanKontrak.php
// SUBCLASS KARYAWAN KONTRAK - DENGAN QUERY WHERE

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
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        
        $this->durasiKontrakBulan = $durasiKontrakBulan;
        $this->agensiPenyalur = $agensiPenyalur;
    }

    // ============================================
    // 3. METHOD OVERRIDING - hitungGajiBersih()
    // ============================================
    /**
     * Logika Bisnis Karyawan Kontrak:
     * Gaji Bersih = hariKerjaMasuk * gajiDasarPerHari
     * (Sistem penggajian murni berdasarkan jumlah hari kehadiran)
     */
    public function hitungGajiBersih() {
        return $this->hariKerjaMasuk * $this->gajiDasarPerHari;
    }

    // ============================================
    // 4. METHOD OVERRIDING - tampilkanProfilKaryawan()
    // ============================================
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

    // ============================================
    // 5. QUERY WHERE DI SUBCLASS
    // ============================================
    /**
     * Method untuk mencari karyawan kontrak dengan durasi tertentu
     */
    public static function cariByDurasiKontrak($koneksi, $durasi) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Kontrak' 
                AND durasi_kontrak_bulan = ?";
        
        $result = $koneksi->query($sql, [$durasi]);
        $daftarKaryawan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $karyawan = new KaryawanKontrak(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['durasi_kontrak_bulan'],
                    $row['agensi_penyalur']
                );
                $daftarKaryawan[] = $karyawan;
            }
        }
        return $daftarKaryawan;
    }

    /**
     * Method untuk mencari karyawan kontrak dengan agensi tertentu
     */
    public static function cariByAgensi($koneksi, $agensi) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Kontrak' 
                AND agensi_penyalur LIKE ?";
        
        $result = $koneksi->query($sql, ['%' . $agensi . '%']);
        $daftarKaryawan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $karyawan = new KaryawanKontrak(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['durasi_kontrak_bulan'],
                    $row['agensi_penyalur']
                );
                $daftarKaryawan[] = $karyawan;
            }
        }
        return $daftarKaryawan;
    }

    /**
     * Method untuk mendapatkan karyawan kontrak dengan gaji tertinggi
     */
    public static function getGajiTertinggi($koneksi) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Kontrak' 
                ORDER BY (hari_kerja_masuk * gaji_dasar_per_hari) DESC 
                LIMIT 1";
        
        $result = $koneksi->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new KaryawanKontrak(
                $row['id_karyawan'],
                $row['nama_karyawan'],
                $row['departemen'],
                $row['hari_kerja_masuk'],
                $row['gaji_dasar_per_hari'],
                $row['durasi_kontrak_bulan'],
                $row['agensi_penyalur']
            );
        }
        return null;
    }

    /**
     * Method untuk mendapatkan total gaji semua karyawan kontrak
     */
    public static function getTotalGaji($koneksi) {
        $sql = "SELECT SUM(hari_kerja_masuk * gaji_dasar_per_hari) as total 
                FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Kontrak'";
        
        $result = $koneksi->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }
}
?>