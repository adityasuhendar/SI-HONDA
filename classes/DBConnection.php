<?php
if (!defined('DB_SERVER')) {
    require_once("../initialize.php");
}

class DBConnection {

    private $host = '127.0.0.1';  // Host (localhost/127.0.0.1)
    private $username = 'root';  // Username default MySQL
    private $password = '';      // Kosong jika tidak ada password
    private $database = 'bpsms_db';  // Nama database, sesuaikan
    private $port = 3306;        // Default port MySQL
    
    public $conn;
    
    public function __construct() {
        // Membuat koneksi
        if (!isset($this->conn)) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                echo 'Koneksi gagal: ' . $this->conn->connect_error;
                exit;
            }
        }
    }

    public function __destruct() {
        // Menutup koneksi saat tidak digunakan
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>

