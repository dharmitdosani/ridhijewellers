<?php
class Product {
	// database connection and table name 
	private $conn;
	private $table_name = "products";

	// product table properties
	public $product_code;
	public $gross_weight;
	public $size;
	public $length;
	public $category_id;
	public $melting;
	public $status;

	// public constructor
	public function __construct($db) {
		$this->conn = $db;
	}

	// add a product to the list - returns last insert id if successful else returns a false
	public function add_product() {

		// making variables safe for query processing
		$this->category_name = mysqli_real_escape_string($this->conn, htmlspecialchars($this->category_name));
		$this->gross_weight = mysqli_real_escape_string($this->conn, htmlspecialchars($this->gross_weight));
		$this->size = mysqli_real_escape_string($this->conn, htmlspecialchars($this->size));
		$this->length = mysqli_real_escape_string($this->conn, htmlspecialchars($this->length));
		$this->status = "active";
		
		// query processing
		$query = "SELECT category_id FROM categories WHERE category_name = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $this->category_name);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$this->category_id = mysqli_real_escape_string($this->conn, htmlspecialchars($row["category_id"]));

		$query = "INSERT INTO " . $this->table_name . " (gross_weight, size, length, category_id, status) VALUES (?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("dddis", $this->gross_weight, $this->size, $this->length, $this->category_id, $this->status);
		if($stmt->execute()) {
			return $this->conn->insert_id;
		}
		else {
			return false;
		}
	}

	// activate a product
	public function activate_product() {

		// making variable safe for query processing
		$this->product_code = mysqli_real_escape_string($this->conn, htmlspecialchars($this->product_code));
		$this->status = "active";

		// query processing
		$query = "UPDATE " . $this->table_name . " SET status = ? WHERE product_code = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("si", $this->status, $this->product_code);
		if($stmt->execute() && $stmt->affected_rows == 1) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>