<?php
if(!defined('DB_SERVER')) {
    require_once("../initialize.php");
}

class DBConnection {

    private $host = 'autorack.proxy.rlwy.net';  // Host from the URL
    private $username = 'root';                  // Username from the URL
    private $password = 'zIcVZvexmpATLkKKDLrPnnihLByJfijI'; // Password from the URL
    private $database = 'railway';               // Database name from the URL
    private $port = 21299;                       // Port from the URL
    
    public $conn;
    
    public function __construct() {
        // Try to create a connection
        if (!isset($this->conn)) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
            
            if ($this->conn->connect_error) {
                echo 'Connection failed: ' . $this->conn->connect_error;
                exit;
            }
        }
    }

    public function __destruct() {
        // Close the connection when done
        $this->conn->close();
    }
}
?>
