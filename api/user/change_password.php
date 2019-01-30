<?php
// required headers
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

// check whether data is complete or not
if(!empty($data->user_name) && !empty($data->old_password) && !empty($data->new_password)) {

	// check whether the user is authentic or not
	$user->user_name = $data->user_name;
	$user->user_password = $data->old_password;
	$stmt = $user->authenticate_user();
	$row = $stmt->fetch_assoc();
	extract($row);
	
	// to check whether the password entered by the user matches with the one saved in the database
	if(strcmp($user_password, $user->user_password) == 0) {
		
		// password matched, changing password to new password
		$user->user_password = $data->new_password;
		if($user->change_password()) {

			// set response code - 200 OK and tell the user
			http_response_code(200);
			echo json_encode(array("message" => "Password changed successfully."));
		}
		else {

			// set response code - 503 Service Unavailable and tell the user
			http_response_code(503);
			echo json_encode(array("message" => "Unable to change password. Service unavailable."));
		}
	}
	else {
		
		// setting response code - 401 Unauthorized and tell the user
		http_response_code(401);
		echo json_encode(array("message" => "Old password incorrect."));
	}
}

// data is incomplete
else {

	// set response code - 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to change password. Data is incomplete."));
}
?>