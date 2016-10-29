<?php
class RegistrationLimiter{
	
	public $timeLimit = 100;
	public function createRegistrationRecord($connection,$ip){
		$sql = " INSERT INTO RegistrationRecords(ip,reg_time)
		Values('".$ip."',UNIX_TIMESTAMP())";
		$connection->query($sql);
	}

	function checkRegistrationRecord($connection,$ip){
		$sql = "SELECT * FROM RegistrationRecords WHERE  ip='".$ip."'";
		$result = $connection->query($sql);
		if((boolean)$result){
			$date = date_create();
			$row = $result->fetch_assoc();
			$time_diff=date_timestamp_get($date) - $row["reg_time"];
			if($time_diff < $this->timeLimit){
				echo "It is time limit to registration from one ip.Sorry.";
				return false;
			} else {
				$sql = "DELETE FROM RegistrationRecords WHERE  ip='".$ip."'";
				$connection->query($sql);
				$this->createRegistrationRecord($connection,$ip);
				return true;
			}
		} else {
			return true;
		}
	}

}


$servername = "localhost";
$username = "root";
$password = "gfdkbiby";
$dbname = "Test_Task_DB";


//Create connection
$conn = mysqli_connect($servername,$username,$password);
if($conn->connect_error){
	die ("Connection failed : " . $conn->connect_error);
}

//Creating db if not exists
$sql = "CREATE DATABASE IF NOT EXISTS ".$dbname;

//Useful for logs
/*
if($conn->query($sql)){
	echo "DB ".$dbname." created succesfully<br>";
} else {
	echo "Error creating DB ".$dbname;
}*/

//Select our db
$conn->select_db($dbname);


$sql = "CREATE TABLE IF NOT EXISTS Users(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20) NOT NULL,
	email VARCHAR(30) NOT NULL,
	password VARCHAR(50) NOT NULL
)";

//Useful for output in logs
/*
if($conn->query($sql)){
	echo "Table Users created succesfully<br>";
} else {
	echo "Error creating Table Users <br>".$conn->error;
}
*/

$sql = "CREATE TABLE IF NOT EXISTS RegistrationRecords(
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		ip VARCHAR(20) NOT NULL,
		reg_time INT(6) NOT NULL
		)";
/*
if($conn->query($sql)){
	echo "Table RegistrationRecords created succesfully<br>";
} else {
	echo "Error creating Table RegistrationRecords <br>".$conn->error;
}
*/


$name = "John1";
$password = "********";
$email = "JohnDo1e@example.com";

$limit = new RegistrationLimiter();
//echo $limit->checkRegistrationRecord($conn,$_SERVER['REMOTE_ADDR']);


if(isset($_POST['name'])) $name = $_POST['name'];
if(isset($_POST['password'])) $password = $_POST['password'];
if(isset($_POST['email'])) $email = $_POST['email'];

$sql = "SELECT * FROM Users WHERE  name='".$name."'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();//NULL or array
if($row){
	echo "This name is busy\nRegistration aborted";
}
else {
	if($limit->checkRegistrationRecord($conn,$_SERVER['REMOTE_ADDR'])){
		$sql = "SELECT * FROM Users WHERE  email='".$email."'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();//NULL or array
		if($row){
			echo "This email is busy\nRegistration aborted";
		} else {
			$password = md5($password);
			$sql = " INSERT INTO Users (name,email,password)
			Values('".$name."','".$email."','".$password."')";
			$conn->query($sql);
			echo "You are succesfully registered";
		}
	}
}

//Close connection;
$conn->close();
?>