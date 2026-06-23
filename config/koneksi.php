<?php
// config/koneksi.php
// Koneksi database dengan OOP murni TANPA getter/setter

class Koneksi {
    // ========== PROPERTI (PRIVATE) ==========
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "pbo_uas_php";
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

            // Binding parameter jika ada
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

    // ========== METHOD INSERT ==========
    public function insert($sql, $params = []) {
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
            $insertId = $stmt->insert_id;
            $stmt->close();

            return $insertId;

        } catch (Exception $e) {
            die("❌ Insert Error: " . $e->getMessage());
        }
    }

    // ========== METHOD UPDATE ==========
    public function update($sql, $params = []) {
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
            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            return $affectedRows;

        } catch (Exception $e) {
            die("❌ Update Error: " . $e->getMessage());
        }
    }

    // ========== METHOD DELETE ==========
    public function delete($sql, $params = []) {
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
            $affectedRows = $stmt->affected_rows;
            $stmt->close();

            return $affectedRows;

        } catch (Exception $e) {
            die("❌ Delete Error: " . $e->getMessage());
        }
    }

    // ========== METHOD TEST KONEKSI ==========
    public function testKoneksi() {
        if ($this->koneksi) {
            echo "✅ Koneksi berhasil!<br>";
            echo "📊 Database: " . $this->database . "<br>";
            echo "🔄 Host: " . $this->host . "<br>";
            return true;
        } else {
            echo "❌ Koneksi gagal!<br>";
            return false;
        }
    }

    // ========== METHOD MENUTUP KONEKSI ==========
    public function tutupKoneksi() {
        if ($this->koneksi) {
            $this->koneksi->close();
            echo "🔒 Koneksi ditutup.<br>";
        }
    }

    // ========== METHOD MENDAPATKAN KONEKSI (TANPA GETTER) ==========
    // Langsung mengembalikan objek koneksi
    public function ambilKoneksi() {
        return $this->koneksi;
    }

    // ========== DESTRUKTOR ==========
    public function __destruct() {
        $this->tutupKoneksi();
    }
}

// ========== INSTANSIASI OBJEK KONEKSI (SINGLETON) ==========
$koneksi = new Koneksi();

// ========== TEST KONEKSI (OPSIONAL) ==========
// $koneksi->testKoneksi();
?>