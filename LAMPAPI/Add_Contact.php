<?php
session_start();
if(!isset($_SESSION["userid"])){
	header("Location: http://contactymanager.shop/");
	sendJsonResult("failure","User Not Signed In");
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

if(!$conn){
	echo "connection Failed";
	die("Connection Failed : " .mysqli_connect_error());
}
// check if userid is set

// if not set send back to login
// make connnection to database
try{
	$stmt = $conn->prepare("INSERT INTO Contacts(firstname,lastname,phone,email,userID) VALUES(?,?,?,?,?)");

	$userInfo = getUserInput();

	$stmt->bind_param("ssssi",$userInfo["firstname"],$userInfo["lastname"],$userInfo["phone"],$userInfo["email"],$userInfo["userid"]);

	$stmt->execute();

	sendJsonResult("success","Contact Added");
}catch(Exception $e){
	sendJsonResult("failure","Something Went Wrong");
}

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
