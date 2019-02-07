<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

// checking if the data exists
if(!empty($data->category_name) && !empty($data->gross_weight) && !empty($data->size) && !empty($data->length)) {

	// setting product variables
	$product->category_name = $data->category_name;
	$product->gross_weight = $data->gross_weight;
	$product->size = $data->size;
	$product->length = $data->length;
	$last_insert_id = $product->add_product();
	
	// product adding failed
	if($last_insert_id === false) {

		// set response code - 503 service unavailable and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to add product. Service unavailable."));
	}
	
	// product adding successful
	else {

		// set response code -  201 created and tell the user
		http_response_code(201);
		echo json_encode(array("message" => "Product added to catalog successfully."));

		// product image addition code here

		// notify customers code here

	}
}

// if any data is incomplete
else {

	// set the response code - 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to add product. Data provided is insufficient."));
}
?>