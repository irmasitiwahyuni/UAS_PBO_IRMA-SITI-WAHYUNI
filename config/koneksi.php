<?php
// config/koneksi.php
// Koneksi database dengan OOP murni TANPA getter/setter

class Koneksi {
    // ========== PROPERTI (PRIVATE) ==========
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_uas_pbo_trpl1b_irma siti wahyuni";
    private $koneksi = null;

    // ========== KONSTRUKTOR ==========
    public function __construct() {
        $this->buatKoneksi();
    }

    // ========== METHOD MEMBUAT KONEKSI ==========
    private function buatKoneksi() {
        try {
            $this->koneksi = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            if ($this->koneksi->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->koneksi->connect_error);
            }

            $this->koneksi->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            die("❌ Error: " . $e->getMessage());
        }
    }

    // ========== METHOD EKSEKUSI QUERY ==========
    public function query($sql, $params = []) {
        try {
            $stmt = $this->koneksi->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare statement gagal: " . $this->koneksi->error);
            }

            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            return $result;

        } catch (Exception $e) {
            die("❌ Query Error: " . $e->getMessage());
        }
    }

    // ========== METHOD TEST KONEKSI ==========
    public function testKoneksi() {
        if ($this->koneksi) {
            return true;
        }
        return false;
    }

    // ========== METHOD MENUTUP KONEKSI ==========
    public function tutupKoneksi() {
        if ($this->koneksi) {
            $this->koneksi->close();
        }
    }

    // ========== DESTRUKTOR ==========
    public function __destruct() {
        $this->tutupKoneksi();
    }
}

// ========== INSTANSIASI OBJEK KONEKSI ==========
$koneksi = new Koneksi();
?>