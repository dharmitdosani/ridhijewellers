<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// getting post data
$data = json_decode(file_get_contents("php://input"));

// check if data is valid
if(!empty($data->user_name)) {

	$user->user_name = $data->user_name;
	// verify user
	if($user->verify_user()) {

		// setting response code - 200 OK
		http_response_code(200);

		// tell the user 
		echo json_encode(array("message" => "User verified."));
	}
	// unable to process request
	else {

		// setting response code - 503 server unavailable
		http_response_code(503);

		// tell the user 
		echo json_encode(array("message" => "Unable to verify user."));
	}
}
// data incomplete
else {
	
	// setting response code - 400 bad request
	http_response_code(400);

	// tell the user 
	echo json_encode(array("message" => "Unable to verify user. Data provided is insufficient."));
}

?>