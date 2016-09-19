<!DOCTYPE html>
<html>
<head>
	<title> Main page </title>
	<link rel="stylesheet" href="SecondTaskStyle.css">
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "TestTaskDB";


$q = $_GET['q'];


if(strstr($q,"c",true)){
	$Qualification_Code = strstr($q,"c",true);
} else {
	$Qualification_Code = "";
}

if(strstr($q,"c")){
	$Cities_Code = strstr($q,"c");
} else {
	$Cities_Code = "";
	$Qualification_Code = $q;
}
if(!empty($Qualification_Code)){
	$Available_Qualifications = "(";

	for($i=0;$i<strlen($Qualification_Code);$i++){
		if(intval($Qualification_Code[$i]) != 0) $Available_Qualifications .= $Qualification_Code[$i].",";
		//echo $Qualification_Code[$i].$i;
	}

	$Available_Qualifications = rtrim($Available_Qualifications,",").")";
	//echo $Available_Qualifications."<br>";

	$Qualification_Condition = "Education.qualification_id IN " . $Available_Qualifications;
} else $Qualification_Condition = "";
if(!empty($Cities_Code)){
	$Available_Cities = "(";
	
	for($i=0;$i<strlen($Cities_Code);$i++){
		if(intval($Cities_Code[$i]) != 0) $Available_Cities .= $Cities_Code[$i].",";
	}

	$Available_Cities = rtrim($Available_Cities,",").")";
	//echo $Available_Cities."<br>";

	$Cities_Condition = "Cities.city_id IN " . $Available_Cities;
} else $Cities_Condition = ""; 

//Create connection
$conn = new mysqli($servername,$username,$password);

//Check connection
if($conn->connect_error) {
	die("Connection failed: " .$conn->connect_error);
}


//echo $Qualification_Condition." ".$Cities_Condition;


//Selecting our db
$conn->select_db($dbname);

echo "<table>";
echo "<tr>";
echo "<th> Name </th>";
echo "<th> Education </th>";
echo "<th> Cities </th>";
echo "</tr>";

if(!empty($Qualification_Condition) && !empty($Cities_Condition)){
	$sql= "SELECT Users.name,Education.education_name,GROUP_CONCAT(Cities.city_name) FROM Users INNER JOIN Education ON Education.qualification_id=Users.qualification_id INNER JOIN UserCities ON Users.user_id=UserCities.user_id INNER JOIN Cities ON UserCities.city_id=Cities.city_id WHERE ". $Qualification_Condition . " AND " . $Cities_Condition . " GROUP BY Users.name,Education.education_name"; //Normal
			//echo "<br>Both not  empty<br>";

	//echo $sql;
} else {
		if(empty($Qualification_Condition) && empty($Cities_Condition)) {
			$sql= "SELECT Users.name,Education.education_name,GROUP_CONCAT(Cities.city_name) FROM Users INNER JOIN Education ON Education.qualification_id=Users.qualification_id INNER JOIN UserCities ON Users.user_id=UserCities.user_id INNER JOIN Cities ON UserCities.city_id=Cities.city_id ". $Qualification_Condition . $Cities_Condition . " GROUP BY Users.name,Education.education_name";
			//echo "<br>Both Empty</br>";
		} else {

			$sql= "SELECT Users.name,Education.education_name,GROUP_CONCAT(Cities.city_name) FROM Users INNER JOIN Education ON Education.qualification_id=Users.qualification_id INNER JOIN UserCities ON Users.user_id=UserCities.user_id INNER JOIN Cities ON UserCities.city_id=Cities.city_id WHERE ". $Qualification_Condition . $Cities_Condition . " GROUP BY Users.name,Education.education_name";
			//echo "<br>One empty</br>";
		}
	}

$result = $conn->query($sql);


if($result->num_rows > 0){
	while($row = $result->fetch_assoc()) {
		echo "<tr>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['education_name'] . "</td>";
		//echo print_r($row);
		echo "<td>" . $row['GROUP_CONCAT(Cities.city_name)'] . "</td>";
		echo "</tr>";
	}
} else {
	echo " 0 results";
}
//echo $sql;
echo"</table>";

?>
</body>
</html>