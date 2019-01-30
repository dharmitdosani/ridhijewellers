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

// check if data is complete
if(!empty($data->user_name)) {
	$user->user_name = $data->user_name;
	if($user->reset_password()) {

		// set http response code - 200 OK and tell the user
		http_response_code(200);
		echo json_encode(array("message" => "Password reset successfully to 'abcd@1234'"));
	}
	else {

		// set the response code - 503 service unavailable and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to reset password. Invalid user name."));
	}
}

// data is incomplete
else {

	// set the response code - 400 Bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Incomplete data. Unable to process request."));
}
?>