<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required files
include_once "../config/Database.php";
include_once "../objects/Order.php";

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// query all orders
$stmt = $order->get_all_orders();
$num = $stmt->num_rows;

// if the number of orders are more than one
if($num>0) {
	$orders_array = array();
	$orders_array["records"] = array();

	// fetching orders from the resultset
	while($row = $stmt->fetch_assoc()) {
		extract($row);
		$order_item = array(
			"order_id" => $order_id,
			"shop_name" => $shop_name,
			"contact_name" => $contact_name,
			"contact_number" => $contact_number,
			"product_code" => $product_code,
			"category_name" => $category_name,
			"quantity" => $quantity
		);
		array_push($orders_array["records"], $order_item);
	}

	// setting response code - 200 OK
	http_response_code(200);

	// show orders in json format
	echo json_encode($orders_array);
}
else {
	
	// setting response code - 404 not found and tell the order
	http_response_code(404);
	echo json_encode(array("message" => "No orders found."));
}
?>