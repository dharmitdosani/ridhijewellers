<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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

// checking if data exists
if(!empty($data->user_name) && !empty($data->user_password) && !empty($data->shop_name) && !empty($data->contact_name) && !empty($data->contact_number) && !empty($data->address)) {

	// check if the user is unique
	if($user->is_unique_user($data->user_name)) {

		// assigning the user data
		$user->user_name = $data->user_name;
		$user->user_password = $data->user_password;
		$user->shop_name = $data->shop_name;
		$user->contact_name = $data->contact_name;
		$user->contact_number = $data->contact_number;
		$user->address = $data->address;

		if($user->create_user()) {

			// set response code - 201 created and tell the user
			http_response_code(201);
			echo json_encode(array("message" => "User successfully created."));
		}
		else {

			// set response code - 503 service unavailable and tell the user
			http_response_code(503);
			echo json_encode(array("message" => "Unable to create user."));	
		}
	}

	// user already exists
	else {

		// set response code - 409 conflict and tell the user
		http_response_code(409);
		echo json_encode(array("message" => "User name already exists."));
	}
}

// tell the user that the data is incomplete
else {

	// set response code to 400 bad request and tell the user
	http_response_code(400);
	echo json_encode(array("message" => "Unable to create user. Data provided is insufficient."));
}
?>