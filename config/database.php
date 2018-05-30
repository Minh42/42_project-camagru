<?php
session_start();

// Singleton to connect db.
class ConnectDb {
  // Hold the class instance.
  private static $instance = null;
  private $conn;
  
  private $DB_DSN = 'mysql:unix_socket=/Users/mpham/goinfre/mamp/mysql/tmp/mysql.sock';
  // private $DB_DSN = 'mysql:unix_socket=/Users/minh/MAMP/mysql/tmp/mysql.sock';
  private $DB_USER = 'root';
  private $DB_PASSWORD = 'root42';
   
  // The db connection is established in the private constructor.
  private function __construct()
  {
    try {
        $this->conn = new PDO($this->DB_DSN, $this->DB_USER, $this->DB_PASSWORD);
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $success[] = "Connected successfully\n"; 
    }
    catch(PDOException $e) {
        $error[] = "Connection failed\n";
    }
  }
  
  public static function getInstance()
  {
    if(!self::$instance)
    {
      self::$instance = new ConnectDb();
    }
   
    return self::$instance;
  }
  
  public function getConnection()
  {
    return $this->conn;
  }
  
  // include the user class, pass in the database connection

}

require_once('user.php');
$instance = ConnectDb::getInstance();
$conn = $instance->getConnection();
$conn->query("use camagru");
$user = new User($conn);

?>