<?php
session_start();
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
try{
	if(checkIfUserExists()){
		//return a json with status failure, message user exists
		sendJsonResult("failure","User Already Exists");
		exit;
	}
	$stmt = $conn->prepare("INSERT INTO Users(firstname,lastname,login,password) VALUES(?,?,?,?)");

	$userInfo = getUserInput();

	$stmt->bind_param("ssss",$userInfo["firstname"],$userInfo["lastname"],$userInfo["login"],$userInfo["password"]);

	$stmt->execute();

	sendJsonResult("success","Account Created Successfully!");

	$stmt2 = $conn->prepare("SELECT id FROM Users where login=?");

	$stmt2->bind_param("s",$userInfo["login"]);

	$stmt2->execute();

	$res = $stmt2->get_result();

	$row = $res->fetch_assoc();

	$_SESSION["userid"] = $row["id"];
	$_SESSION["firstname"] = $userInfo["firstname"];
	$_SESSION["lastname"] = $userInfo["lastname"];
	exit;
}catch(Exception $e){

}


function checkIfUserExists(){
	$servername = $_ENV['DB_HOST'];
	$sqlUser = $_ENV['DB_USER'];
	$sqlPass = $_ENV['DB_PASS'];
	$conn = new mysqli($servername, $sqlUser,$sqlPass,$_ENV['DB_NAME']);
	if(!$conn){
		echo "connection Failed";
		die("Connection Failed : " .mysqli_connect_error());
	}
	try{
		$stmt = $conn->prepare("SELECT login FROM Users WHERE login=?");
		
		// get user input
		$userInfo = getUserInput();

		$stmt->bind_param("s",$userLogin);

		$userLogin = $userInfo["login"];

		$stmt->execute();

		$result = $stmt->get_result();

		$row = $result->fetch_assoc();

		// if user exist return true
		if($row){
			return true;
		}
		return false;
		
}catch(Exception $e){

}
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
