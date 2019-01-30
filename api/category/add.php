<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//required files
include_once "../config/Database.php";
include_once "../objects/Category.php";

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

//getting post data
$data = json_decode(file_get_contents("php://input"));

//checking if the data exists
if(!empty($data->category_name)) {
	
	//set data into the category variables
	$category->category_name = $data->category_name;
	if($category->insert()) {
		
		//set response code 201 created and tell the user
		http_response_code(201);
		echo json_encode(array("message" => "Category was created successfully."));
	}
	else {
		
		//set response code 503 service unavailable and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to create category."));
	}
}

//tell the user that the data is incomplete
else {
	
	//set response code to 400 bad request ans tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to create category. Data provided is insufficient."));
}
?>