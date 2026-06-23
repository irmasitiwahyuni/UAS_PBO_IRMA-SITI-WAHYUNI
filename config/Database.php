<?php
// config/Database.php
// Koneksi database dengan OOP murni tanpa getter/setter

class Database {
    // ========== PROPERTI (PRIVATE) ==========
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "pbo_uas_php";
    private $connection = null;

    // ========== KONSTRUKTOR ==========
    public function __construct() {
        $this->connect();
    }

    // ========== METHOD UNTUK KONEKSI ==========
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->host, 
                $this->username, 
                $this->password, 
                $this->database
            );

            if ($this->connection->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->connection->connect_error);
            }

            // Set charset ke UTF-8
            $this->connection->set_charset("utf8mb4");
            
            echo "✅ Database connected successfully!<br>";

        } catch (Exception $e) {
            die("❌ Error: " . $e->getMessage());
        }
    }

    // ========== METHOD UNTUK EKSEKUSI QUERY ==========
    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare statement gagal: " . $this->connection->error);
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

    // ========== METHOD UNTUK INSERT ==========
    public function insert($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare statement gagal: " . $this->connection->error);
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

    // ========== METHOD UNTUK MENUTUP KONEKSI ==========
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
            echo "🔒 Database connection closed.<br>";
        }
    }

    // ========== DESTRUKTOR ==========
    public function __destruct() {
        $this->closeConnection();
    }

    // ========== METHOD UNTUK TEST KONEKSI ==========
    public function testConnection() {
        if ($this->connection) {
            echo "✅ Connection test: SUCCESS<br>";
            echo "📊 Database: " . $this->database . "<br>";
            echo "🔄 Host: " . $this->host . "<br>";
        }
    }

    // ========== METHOD UNTUK MENDAPATKAN KONEKSI (TANPA GETTER) ==========
    // Langsung mengembalikan objek koneksi untuk query kompleks
    public function getConnectionObject() {
        return $this->connection;
    }
}

// ========== INISIALISASI KONEKSI (SINGLETON PATTERN) ==========
$database = new Database();
?>