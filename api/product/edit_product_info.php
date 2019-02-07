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
if(!empty($data->product_code) && !empty($data->gross_weight) && !empty($data->size) && !empty($data->length) && !empty($data->category_name)) {
	
	// assigning product variable values
	$product->product_code = $data->product_code;
	$product->gross_weight = $data->gross_weight;
	$product->size = $data->size;
	$product->length = $data->length;
	$product->category_name = $data->category_name;
	
	if($product->edit_product_info()) {

		// set response code - 200 OK and tell the user
		http_response_code(200);
		echo json_encode(array("message" => "Successfully edited product info."));
	}
	else {

		// set response code - 503 bad request and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to edit product info. Service unavailable."));
	}
}

// data missing
else {

	// set response code - 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to edit product info. Data provided is insufficient."));
}
?>