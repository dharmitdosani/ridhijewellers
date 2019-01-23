<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//required files
include_once "../config/Database.php";
include_once "../objects/Category.php";

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

//query all categories
$stmt = $category->read();
$num = $stmt->num_rows;

//if the number of categories are more than one
if($num>0) {
	$categories_array = array();
	$categories_array["records"] = array();

	//fetching categories from the array
	while($row = $stmt->fetch_assoc()) {
		extract($row);
		$category_item = array(
			"category_id" => $category_id,
			"category_name" => $category_name
		);
		array_push($categories_array["records"], $category_item);
	}

	// setting response code - 200 OK
	http_response_code(200);

	//show categories in json format
	echo json_encode($categories_array);
}
else {
	// setting response code - 404 not found
	http_response_code(404);

	// tell the user
	echo json_encode(array("message" => "No categories found"));
}

?>