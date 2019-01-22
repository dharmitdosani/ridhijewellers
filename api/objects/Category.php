<?php

class Category {
	//connection to the database and table name
	private $conn;
	private $table_name = "categories";	
	
	//category table properties
	public $category_id;
	public $category_name;

	//
	public function __construct($db) {
		$this->conn = $db;
	}

	public function insert() {

		$this->category_name = htmlspecialchars($this->category_name);
		$this->category_name = mysqli_real_escape_string($this->conn,$this->category_name);

		$query = "INSERT INTO " . $this->table_name . "(category_name) VALUES(?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s",$this->category_name);

		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}

	}
}
?>