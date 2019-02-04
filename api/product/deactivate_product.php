<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/Product.php";

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// getting post data
$data = json_decode(file_get_contents("php://input"));

// checking if all the necessary data is present
if(!empty($data->product_code)) {
	
	// assigning product variable values
	$product->product_code = $data->product_code;
	if($product->deactivate_product()) {

		// set response code - 200 OK and tell the user
		http_response_code(200);
		echo json_encode(array("message" => "Successfully deactivated product."));
	}
	else {

		// set response code - 503 bad request and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to deactivate product. Service unavailable."));
	}
}

// data missing
else {

	// set response code - 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to deactivate product. Data provided is insufficient."));
}
?>