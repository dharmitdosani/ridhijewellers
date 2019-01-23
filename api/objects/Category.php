<?php
class Category {
	// connection to the database and table name
	private $conn;
	private $table_name = "categories";	
	
	// category table properties
	public $category_id;
	public $category_name;

	// consturctor
	public function __construct($db) {
		$this->conn = $db;
	}

	// insert one category into the table
	public function insert() {
		// making the category name safe for the query
		$this->category_name = mysqli_real_escape_string($this->conn, htmlspecialchars($this->category_name));

		// query processing
		$query = "INSERT INTO " . $this->table_name . "(category_name) VALUES(?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $this->category_name);

		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	// reading all the categories from the table
	public function read() {
		// query processing
		$query = "SELECT * FROM " . $this->table_name;
		$stmt = $this->conn->query($query);
		// $stmt->execute(); // yeh use nahi karna hai
		return $stmt;
	}
}
?>