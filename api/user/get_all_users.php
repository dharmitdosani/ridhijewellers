<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

//query all categories
$stmt = $user->get_all_users();
$num = $stmt->num_rows;

//if the number of categories are more than one
if($num>0) {
	$users_array = array();
	$users_array["records"] = array();

	//fetching categories from the array
	while($row = $stmt->fetch_assoc()) {
		extract($row);
		$user_item = array(
			"user_id" => $user_id,
			"user_name" => $user_name,
			"shop_name" => $shop_name,
			"contact_name" => $contact_name,
			"contact_number" => $contact_number,
			"address" => $address,
			"status" => $status
		);
		array_push($users_array["records"], $user_item);
	}

	// setting response code - 200 OK
	http_response_code(200);

	//show categories in json format
	echo json_encode($users_array);
}
else {
	// setting response code - 404 not found and tell the user
	http_response_code(404);
	echo json_encode(array("message" => "No categories found."));
}
?>