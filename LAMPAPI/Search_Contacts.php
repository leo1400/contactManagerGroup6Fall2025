<?php
// check if user is logged in 
session_start();
if(!isset($_SESSION["userid"])){
	sendJsonResult("failure","User Not Logged In");
	header("Location: http://contactymanager.shop/");
	exit;
}
header("Content-Type: application/json");

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
// replace each with corresponding to server
$servername = $_ENV['DB_HOST'];
$sqlUser = $_ENV['DB_USER'];
$sqlPass = $_ENV['DB_PASS'];

$conn = new mysqli($servername, $sqlUser,$sqlPass,$_ENV['DB_NAME']);
try{
	$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (firstname LIKE ? OR lastname LIKE ? OR email LIKE ? OR phone LIKE ? OR CONCAT_WS(' ', firstname, lastname) LIKE ? OR CONCAT_WS(' ', lastname, firstname) LIKE ? OR REPLACE(CONCAT_WS(' ', firstname, lastname), ' ', '') LIKE ? OR REPLACE(CONCAT_WS(' ', lastname, firstname), ' ', '') LIKE ?) and userID=?");

	$userSearch = getUserInput();
	$rawSearch = isset($userSearch["search"]) ? $userSearch["search"] : '';
	$normalizedSearch = preg_replace('/\s+/', ' ', trim($rawSearch)); // collapse repeated whitespace
	$searchWithSpaces = '%' . $normalizedSearch . '%';
	$searchWithoutSpaces = '%' . str_replace(' ', '', $normalizedSearch) . '%';
	$userSearch["userID"] = $_SESSION["userid"];

	$stmt->bind_param("ssssssssi",$searchWithSpaces,$searchWithSpaces,$searchWithSpaces,$searchWithSpaces,$searchWithSpaces,$searchWithSpaces,$searchWithoutSpaces,$searchWithoutSpaces,$userSearch["userID"] );

	$stmt->execute();

	$result = $stmt->get_result();

	$contacts = array();
	while($row = $result->fetch_assoc()){
		$contacts[] = ["id" => $row["id"],"firstname" => $row["firstname"],"lastname" => $row["lastname"],"phone" => $row["phone"],"email" => $row["email"]];
	}
	if(count($contacts) == 0){
		sendJsonResult("failure","Contact not found");
		exit;
	}
	
	$jsonRes = json_encode($contacts);
	echo $jsonRes;
	exit;
}catch(Exception $e){
}
// incoming json should look like this
// {
//	"query":string
//}
function getUserInput(){
	// you have to get this from php://input
	$userInfo = json_decode(file_get_contents("php://input"),true);
	return $userInfo;
}
function sendJsonResult($status,$message){
	$res = ['status'=>$status,'message'=>$message];
	$jsonRes = json_encode($res);
	echo $jsonRes;
}
?>
