<!DOCTYPE html>
<html>
<head>
	<title> Main page </title>
	<link rel="stylesheet" href="SecondTaskStyle.css">
	<script>
		//Return Difference between string_b and string_a
		/*function getStringDifference(string_a,string_b) {
			var first_occurance = string_b.indexOf(string_a);
			if(first_occurance == 0){
				return string_b.substring(string_a.length);
			} else {
				return string_b.substring(0,first_occurance)+string_b.substring(first_occurance+string_a.length);
			}
		}*/
		function makeCondition(){
			var options = document.querySelectorAll("[type='checkbox']");
			var condition="";

			for(i=0;i<options.length;i++){
				if(options[i].checked) condition += options[i].value;
			}
			alert(condition);
			createResponseTable(condition);
		}
		function createResponseTable(str) {
			if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            var xmlhttp = new XMLHttpRequest();
        	} else {
            // code for IE6, IE5
            var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        	}
			xmlhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200){
					document.getElementById('resultTable').innerHTML = this.responseText;
				}
			};
			xmlhttp.open("GET","getTableUpdate.php?q="+str,true);
			xmlhttp.send();
		}
	</script>
</head>
<body>
<?php
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


//Selecting our db
$conn->select_db($dbname);

$sql = "SELECT * FROM Education";

echo "<form>";
echo "<p> Choose Qualification : <p>";

$result = $conn->query($sql);

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()) {
		echo "<input type='checkbox' onchange='makeCondition()' value='q". $row['qualification_id'] . "'> " . $row['education_name'] . "<br>";
	}
} else {
	echo " 0 results";
}

$sql = "SELECT * FROM Cities";

$result = $conn->query($sql);

echo"<br>";
echo"<p> Choose City : <p>";

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()) {
		echo "<input type='checkbox' onchange='makeCondition()' value='c". $row['city_id'] . "'> " . $row['city_name'] . "<br>";
	}
} else {
	echo " 0 results";
}

echo "</form>";
echo "<div id='resultTable'>";
echo "<table>";
echo "<tr>";
echo "<th> Name </th>";
echo "<th> Education </th>";
echo "<th> Cities </th>";
echo "</tr>";

$sql= "SELECT Users.name,Education.education_name,GROUP_CONCAT(Cities.city_name) FROM Users INNER JOIN Education ON Education.qualification_id=Users.qualification_id INNER JOIN UserCities ON Users.user_id=UserCities.user_id INNER JOIN Cities ON UserCities.city_id=Cities.city_id GROUP BY Users.name,Education.education_name";

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
echo"</table>";
echo"</div>";

?>
</body>
</html>