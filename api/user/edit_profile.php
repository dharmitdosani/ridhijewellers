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

// check if all the editable fields are present 
if(!empty($data->user_name) && !empty($data->contact_name) && !empty($data->contact_number) && !empty($data->address)) {

	// setting user variables from data fetched
	$user->user_name = $data->user_name;
	$user->contact_name = $data->contact_name;
	$user->contact_number = $data->contact_number;
	$user->address = $data->address;

	if($user->edit_profile()) {

		// set response code - 200 OK and tell the user
		http_response_code(200);
		echo json_encode(array("message" => "User profile updated successfully."));
	}

	else {

		// set the response code - 503 service unavailable and tell the user
		http_response_code(503);
		echo json_encode(array("message" => "Unable to update user profile. Service unavailable."));
	}
}
// data incomplete
else {

	// set response code 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to update user profile. Data provided is insufficient."));
}
?>