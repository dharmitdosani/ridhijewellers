<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/Order.php";

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// getting post data
$data = json_decode(file_get_contents("php://input"));

// checking if data exists
if(!empty($data->product_code) && !empty($data->user_id) && !empty($data->quantity)) {

	$order->product_code = $data->product_code;
	$order->user_id = $data->user_id;
	$order->quantity = $data->quantity;

	if($order->place_order()) {

		// set response code - 201 created and tell the user
		http_response_code(201);
		echo json_encode(array("message" => "Order placed successfully."));
	}
	else {

		// set response code - 503 service unavailable and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to place order. Service unavailable"));	
	}
}

//tell the user that the data is incomplete
else {

	// set response code to 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to place order. Data provided is insufficient."));
}
?>