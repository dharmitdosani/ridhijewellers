<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/Order.php";

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// if delete is successful
if($order->delete_all_orders()) {

	// setting response code - 200 OK and tell the user
	http_response_code(200);
	echo json_encode(array("message" => "Deleted all orders successfully."));
}
else {

	// setting response code - 503 service unavailable and tell the user
	http_response_code(503);
	echo json_encode(array("message" => "Unable to delete orders. Service unavailable."));
}
?>