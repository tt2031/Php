<?php

$servername = "localhost";
$username = "root";
$password = "gfdkbiby";
$dbname = "HotelsTestTaskDB";


//Create connection
$conn = mysqli_connect($servername,$username,$password);

if($conn->connect_error){
	die ("Connection failed : " . $conn->connect_error);
}


$sql = "DROP DATABASE IF EXISTS " . $dbname ;
$conn->query($sql);

$sql = "CREATE DATABASE " . $dbname;

if($conn->query($sql) === TRUE ){
	echo "DB created succesfully<br>";
} else {
	echo "Error creating DB " . $conn->error();
}

//Select our db
$conn->select_db($dbname);


//Creating Comments table
$sql = "CREATE TABLE Comments
(
	comment_id INT(6) UNSIGNED AUTO_INCREMENT,
	username VARCHAR(30) NOT NULL,
	comment_text VARCHAR(100) NOT NULL,
	reference_comment_id INT(6) UNSIGNED,
	PRIMARY KEY (comment_id)
) ENGINE=INNODB";

if($conn->query($sql) === TRUE ){
	echo "Table Comments created succesfully<br>";
} else {
	echo "Error creating Comments table " . $conn->error();
}


//Close connection;
$conn->close();
?>

