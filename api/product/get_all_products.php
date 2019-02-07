<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/Product.php";

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

// query all categories
$stmt = $product->get_all_products();
$num = $stmt->num_rows;

// if the number of categories are more than one
if($num>0) {
	$product_array = array();
	$product_array["records"] = array();

	// fetching products from the array
	while($row = $stmt->fetch_assoc()) {
		extract($row);
		$product_item = array(
			"product_code" => $product_code,
			"gross_weight" => $gross_weight,
			"size" => $size,
			"length" => $length,
			"category_name" => $category_name,
			"melting" => $melting,
			"status" => $status
		);
		array_push($product_array["records"], $product_item);
	}

	// setting response code - 200 OK
	http_response_code(200);

	// show categories in json format
	echo json_encode($product_array);
}
else {
	
	// setting response code - 404 not found and tell the user
	http_response_code(404);
	echo json_encode(array("message" => "No categories found."));
}
?>