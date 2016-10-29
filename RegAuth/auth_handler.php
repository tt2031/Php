<?php
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
}*/

$name = "John1";
$password = "********";
$email = "JohnDo1e@example.com";


if(isset($_POST['name'])) $name = $_POST['name'];
if(isset($_POST['password'])) $password = $_POST['password'];
if(isset($_POST['email'])) $email = $_POST['email'];
//print_r($_POST);

$password = md5($password);
$sql = "SELECT * FROM Users WHERE  name='".$name."' AND password='".$password."'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();//NULL or array
if($row){
	echo "You succesfully entered";
	//header('Location: succes.php');
} else {
	echo "Something went wrong,maybe you are not registered.Otherwise,please,check your auth. data.";
}
//Close connection;
$conn->close();
?>