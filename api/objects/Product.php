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

		$query = "INSERT INTO " . $this->table_name . " (gross_weight, size, length, category_id, status) VALUES (?, ?, ?, (SELECT category_id FROM categories WHERE category_name = ?), ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("dddss", $this->gross_weight, $this->size, $this->length, $this->category_name, $this->status);
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

	// deactivate a product
	public function deactivate_product() {

		// making variable safe for query processing
		$this->product_code = mysqli_real_escape_string($this->conn, htmlspecialchars($this->product_code));
		$this->status = "inactive";

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

	// edit product info
	public function edit_product_info() {

		// making variable safe for query processing
		$this->product_code = mysqli_real_escape_string($this->conn, htmlspecialchars($this->product_code));
		$this->gross_weight = mysqli_real_escape_string($this->conn, htmlspecialchars($this->gross_weight));
		$this->size = mysqli_real_escape_string($this->conn, htmlspecialchars($this->size));
		$this->length = mysqli_real_escape_string($this->conn, htmlspecialchars($this->length));
		$this->category_name = mysqli_real_escape_string($this->conn, htmlspecialchars($this->category_name));

		// query processing
		$query = "UPDATE " . $this->table_name . " SET gross_weight = ?, size = ?, length = ?, category_id = (SELECT category_id FROM categories WHERE category_name = ?) WHERE product_code = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("dddsi", $this->gross_weight, $this->size, $this->length, $this->category_name, $this->product_code);
		if($stmt->execute() && $stmt->affected_rows == 1) {
			return true;
		}
		else {
			return false;
		}	
	}

	// get all products from catalog
	public function get_all_products() {

		// query processing
		$query = "SELECT a.product_code, a.gross_weight, a.size, a.length, b.category_name, a.melting, a.status FROM " . $this->table_name . " a JOIN categories b ON a.category_id = b.category_id ORDER BY a.product_code ASC";
		$stmt = $this->conn->query($query);
		return $stmt;
	}
}
?>