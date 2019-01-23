<?php

class User {
	// database connection and table name
	private $conn;
	private $table_name = "users";

	// user properties
	public $user_id;
	public $user_name;
	public $user_password;
	public $shop_name;
	public $contact_name;
	public $contact_number;
	public $address;
	public $status;

	// constructor
	public function __construct($db) {
		$this->conn = $db;
	}

	// create user
	public function create_user() {
		// making the variables safe for the query
		$this->user_name = mysqli_real_escape_string($this->conn, htmlspecialchars($this->user_name));
		// check for what to do for password encryption
		$this->user_password = mysqli_real_escape_string($this->conn, htmlspecialchars($this->user_password));
		$this->shop_name = mysqli_real_escape_string($this->conn, htmlspecialchars($this->shop_name));
		$this->contact_name = mysqli_real_escape_string($this->conn, htmlspecialchars($this->contact_name));
		$this->contact_number = mysqli_real_escape_string($this->conn, htmlspecialchars($this->contact_number));
		$this->address = mysqli_real_escape_string($this->conn, htmlspecialchars($this->address));

		// query processing
		$query = "INSERT INTO " . $this->table_name . "(user_name, user_password, shop_name, contact_name, contact_number, address) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssssss", $this->user_name, $this->user_password, $this->shop_name, $this->contact_name, $this->contact_number, $this->address);

		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	// checking if the user is unique or not
	public function is_unique_user($user_name) {
		$user_name = strtolower($user_name);
		$query = "SELECT user_name FROM " . $this->table_name;
		$stmt = $this->conn->query($query);
		$num = $stmt->num_rows;
		//if the number of users are more than one
		if($num>0) {
			$user_name_array = array();
			//fetching usernames from the array
			while($row = $stmt->fetch_assoc()) {
				array_push($user_name_array, strtolower($row["user_name"]));
			}
			if(in_array($user_name, $user_name_array)) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}
	}
}
?>