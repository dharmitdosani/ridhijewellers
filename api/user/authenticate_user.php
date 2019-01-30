<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// getting json data
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_name) && !empty($data->user_password)) {
	$user->user_name = $data->user_name;
	$user->user_password = $data->user_password;
	$stmt = $user->authenticate_user();
	$row = $stmt->fetch_assoc();
	extract($row);
	
	// to check whether the password entered by the user matches with the one saved in the database
	if(strcmp($user_password, $user->user_password) == 0) {
		
		// setting response code - 200 OK and telling the user
		http_response_code(200);
		echo json_encode(array("message" => "Login Successful"));
	}
	else {
		
		// setting response code - 401 Unauthorized and tell the user
		http_response_code(401);
		echo json_encode(array("message" => "Username or password incorrect."));
	}
}
else {
	
	// setting response code - 400 bad request and telling the user
	http_response_code(400);
	echo json_encode(array("message" => "Insufficient data to authenticate user."));
}
?>