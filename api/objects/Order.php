<?php
class Order {
	// database connection and table name 
	private $conn;
	private $table_name = "orders";

	// product table properties
	public $order_id;
	public $product_code;
	public $user_id;
	public $quantity;

	// public constructor
	public function __construct($db) {
		$this->conn = $db;
	}

	// placing an order 
	public function place_order() {

		// making variables safe for query processing
		$this->product_code = mysqli_real_escape_string($this->conn, htmlspecialchars($this->product_code));
		$this->user_id = mysqli_real_escape_string($this->conn, htmlspecialchars($this->user_id));
		$this->quantity = mysqli_real_escape_string($this->conn, htmlspecialchars($this->quantity));

		// query processing
		$query = "INSERT INTO " . $this->table_name . " (product_code, user_id, quantity) VALUES (?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iii", $this->product_code, $this->user_id, $this->quantity);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function get_all_orders() {

		// query processing
		$query = "SELECT a.order_id, b.shop_name, b.contact_name, b.contact_number, c.product_code, d.category_name, a.quantity FROM " . $this->table_name . " a JOIN users b ON a.user_id = b.user_id JOIN products c ON a.product_code = c.product_code JOIN categories d ON d.category_id = c.category_id ORDER BY a.order_id ASC";
		$stmt = $this->conn->query($query);
		return $stmt;
	}

	public function delete_all_orders() {

		// query processing 
		$query = "TRUNCATE TABLE " . $this->table_name;
		$stmt = $this->conn->query($query);
		return $stmt;
	}
}
?>