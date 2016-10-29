<?php
//
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TestTaskDB";

//Create connection
$conn = new mysqli($servername,$username,$password);

//Check connection
if($conn->connect_error) {
	die("Connection failed: " .$conn->connect_error);
}


//Deleting DataBase if needed
//$conn->query("DROP DATABASE " . $dbname);

//Creating DataBase
$CreateDB = " CREATE DATABASE " . $dbname;

if($conn->query($CreateDB) === TRUE) {
	echo "DB created succesfully<br>";
} else {
	echo "Error creating DB: " . $conn->error();
}

//Selecting our db
$conn->select_db($dbname);


//Create tables
$CreateTable = "CREATE TABLE Education
(
	qualification_id INT(6)  UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	PRIMARY KEY (qualification_id)
) ENGINE=INNODB";

if($conn->query($CreateTable) === TRUE) {
	echo "Table Education created succesfully<br>";
} else {
	echo "Error creating Education table: " . $conn->error();
}


$CreateTable = "CREATE TABLE Cities
(
	city_id INT(6) UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	PRIMARY KEY (city_id)
) ENGINE=INNODB;";

if($conn->query($CreateTable) === TRUE) {
	echo "Table Cities created succesfully<br>";
} else {
	echo "Error creating Cities table: " . $conn->error();
}


$CreateTable = "CREATE TABLE Users
(
	user_id INT(6) UNSIGNED NOT NULL,
    name VARCHAR(30) NOT NULL,
    qualification_id INT(6) UNSIGNED NOT NULL,
    PRIMARY KEY (user_id),
    FOREIGN KEY (qualification_id)
    	REFERENCES Education(qualification_id)
)ENGINE=INNODB;";

if($conn->query($CreateTable) === TRUE) {
	echo "Table Users created succesfully<br>";
} else {
	echo "Error creating Users table: " . $conn->error();
}


$CreateTable = "CREATE TABLE UserCities
(
	user_id INT(6) UNSIGNED NOT NULL ,
	city_id INT(6) UNSIGNED NOT NULL ,
	PRIMARY KEY (user_id, city_id),
	FOREIGN KEY (user_id) REFERENCES Users(user_id),
	FOREIGN KEY (city_id) REFERENCES Cities(city_id)
)ENGINE=INNODB;";

if($conn->query($CreateTable) === TRUE) {
	echo "Table UserCities created succesfully<br>";
} else {
	echo "Error creating UserCities table: " . $conn->error();
}

$conn->close();
?>