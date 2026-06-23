<?php
// models/KaryawanTetap.php
// SUBCLASS KARYAWAN TETAP - DENGAN QUERY WHERE

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
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, 
                           $hariKerjaMasuk, $gajiDasarPerHari);
        
        $this->tunjanganKesehatan = $tunjanganKesehatan;
        $this->opsiSahamId = $opsiSahamId;
    }

    // ============================================
    // 3. METHOD OVERRIDING - hitungGajiBersih()
    // ============================================
    /**
     * Logika Bisnis Karyawan Tetap:
     * Gaji Bersih = (hariKerjaMasuk * gajiDasarPerHari) + tunjanganKesehatan
     * (Mendapatkan tambahan tunjangan kesehatan/keluarga yang besarnya bervariasi)
     */
    public function hitungGajiBersih() {
        $gajiPokok = $this->hariKerjaMasuk * $this->gajiDasarPerHari;
        return $gajiPokok + $this->tunjanganKesehatan;
    }

    // ============================================
    // 4. METHOD OVERRIDING - tampilkanProfilKaryawan()
    // ============================================
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

    // ============================================
    // 5. QUERY WHERE DI SUBCLASS
    // ============================================
    /**
     * Method untuk mencari karyawan tetap dengan tunjangan di atas nilai tertentu
     */
    public static function cariByTunjanganMinimal($koneksi, $minTunjangan) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Tetap' 
                AND tunjangan_kesehatan >= ?";
        
        $result = $koneksi->query($sql, [$minTunjangan]);
        $daftarKaryawan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $karyawan = new KaryawanTetap(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['tunjangan_kesehatan'],
                    $row['opsi_saham_id']
                );
                $daftarKaryawan[] = $karyawan;
            }
        }
        return $daftarKaryawan;
    }

    /**
     * Method untuk mencari karyawan tetap dengan opsi saham tertentu
     */
    public static function cariByOpsiSaham($koneksi, $opsiSaham) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Tetap' 
                AND opsi_saham_id = ?";
        
        $result = $koneksi->query($sql, [$opsiSaham]);
        $daftarKaryawan = [];
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $karyawan = new KaryawanTetap(
                    $row['id_karyawan'],
                    $row['nama_karyawan'],
                    $row['departemen'],
                    $row['hari_kerja_masuk'],
                    $row['gaji_dasar_per_hari'],
                    $row['tunjangan_kesehatan'],
                    $row['opsi_saham_id']
                );
                $daftarKaryawan[] = $karyawan;
            }
        }
        return $daftarKaryawan;
    }

    /**
     * Method untuk mendapatkan karyawan tetap dengan gaji tertinggi
     */
    public static function getGajiTertinggi($koneksi) {
        $sql = "SELECT * FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Tetap' 
                ORDER BY ((hari_kerja_masuk * gaji_dasar_per_hari) + tunjangan_kesehatan) DESC 
                LIMIT 1";
        
        $result = $koneksi->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new KaryawanTetap(
                $row['id_karyawan'],
                $row['nama_karyawan'],
                $row['departemen'],
                $row['hari_kerja_masuk'],
                $row['gaji_dasar_per_hari'],
                $row['tunjangan_kesehatan'],
                $row['opsi_saham_id']
            );
        }
        return null;
    }

    /**
     * Method untuk mendapatkan total gaji semua karyawan tetap
     */
    public static function getTotalGaji($koneksi) {
        $sql = "SELECT SUM((hari_kerja_masuk * gaji_dasar_per_hari) + tunjangan_kesehatan) as total 
                FROM tabel_karyawan 
                WHERE jenis_karyawan = 'Tetap'";
        
        $result = $koneksi->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        }
        return 0;
    }
}
?>