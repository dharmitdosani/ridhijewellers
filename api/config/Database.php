<?php
class Database{

	// database class members
	private $host = "localhost";
	private $username = "root";
	private $password = "";
	private $database_name = "test_ridhi_jewellers";
	
	public $conn;

	public function getConnection() {
		
		//creating a connection with the database
		$this->conn = new mysqli($this->host, $this->username, $this->password, $this->database_name);
		
		//checking if the connection was made or not
		if($this->conn->connect_error) {
			die('Connection with the database failed: '.$this->conn->connect_error);
		}
		return $this->conn;
	}
}
?>