<?php
// models/KaryawanMagang.php
// SUBCLASS KARYAWAN MAGANG - DENGAN QUERY WHERE

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
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        
        $this->uangSakuBulanan = $uangSakuBulanan;
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }

    // ============================================
    // 3. METHOD OVERRIDING - hitungGajiBersih()
    // ============================================
    /**
     * Logika Bisnis Karyawan Magang:
     * Gaji Bersih = (hariKerjaMasuk * gajiDasarPerHari) * 0.80
     * (Menerima potongan upah sebesar 20% dari plafon harian 
     *  untuk biaya program orientasi, pelatihan, atau asuransi kerja intern)
     */
    public function hitungGajiBersih() {
        $gajiKotor = $this->hariKerjaMasuk * $this->gajiDasarPerHari;
        return $gajiKotor * 0.80; // Potongan 20%
    }

    // ============================================
    // 4. METHOD OVERRIDING - tampilkanProfilKaryawan()
    // ============================================
    public function tampilkanProfilKaryawan() {
        $potongan = $this->hitungGajiBersih() * 0.20;
        
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
        echo "📊 DETAIL POTONGAN:<br>";
        echo "Gaji Kotor         : Rp " . number_format($this->hariKerjaMasuk * $this->gajiDasarPerHari, 0, ',', '.') . "<br>";
        echo "Potongan 20%       : Rp " . number_format($potongan, 0, ',', '.') . "<br>";
        echo "----------------------------------------<br>";
        echo "💰 TOTAL GAJI BERSIH: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "<br>";
        echo "========================================<br><br>";
    }

    // ============================================
    // 5. QUERY WHERE DI SUBCLASS
    // ============================================
    /**
     * Method untuk mencari karyawan magang dengan uang saku di atas nilai tertentu
     */
    public static function cariByUangSakuMinimal($koneksi, $minUangSaku) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Magang' 
                AND uang_saku_bulanan >= ?";
        
        $result = $koneksi->query($sql, [$minUangSaku]);
        $daftarKaryawan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $karyawan = new KaryawanMagang(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['uang_saku_bulanan'],
                    $row['sertifikat_kampus_merdeka']
                );
                $daftarKaryawan[] = $karyawan;
            }
        }
        return $daftarKaryawan;
    }

    /**
     * Method untuk mencari karyawan magang dengan sertifikat tertentu
     */
    public static function cariBySertifikat($koneksi, $sertifikat) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Magang' 
                AND sertifikat_kampus_merdeka LIKE ?";
        
        $result = $koneksi->query($sql, ['%' . $sertifikat . '%']);
        $daftarKaryawan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $karyawan = new KaryawanMagang(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['uang_saku_bulanan'],
                    $row['sertifikat_kampus_merdeka']
                );
                $daftarKaryawan[] = $karyawan;
            }
        }
        return $daftarKaryawan;
    }

    /**
     * Method untuk mendapatkan karyawan magang dengan gaji tertinggi
     */
    public static function getGajiTertinggi($koneksi) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Magang' 
                ORDER BY ((hari_kerja_masuk * gaji_dasar_per_hari) * 0.80) DESC 
                LIMIT 1";
        
        $result = $koneksi->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new KaryawanMagang(
                $row['id_karyawan'],
                $row['nama_karyawan'],
                $row['departemen'],
                $row['hari_kerja_masuk'],
                $row['gaji_dasar_per_hari'],
                $row['uang_saku_bulanan'],
                $row['sertifikat_kampus_merdeka']
            );
        }
        return null;
    }

    /**
     * Method untuk mendapatkan total gaji semua karyawan magang
     */
    public static function getTotalGaji($koneksi) {
        $sql = "SELECT SUM((hari_kerja_masuk * gaji_dasar_per_hari) * 0.80) as total 
                FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Magang'";
        
        $result = $koneksi->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }
}
?>