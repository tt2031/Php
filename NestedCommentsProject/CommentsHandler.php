<!DOCTYPE html>
<html>
<head>
	<title> Main page </title>
	<link rel="stylesheet" href="test.css">
</head>
<body>
<?php

//Place and outputs comments in right order
function Comment_Output($Comment_array,$counter,$current_connection){
	while($record = $Comment_array->fetch_assoc()) {
		//Make indents for nested comments
		//$counter -- nesting level
		//NestedComments class is used for show/hide nested comments
		if($counter>0){
			$margin = 30*$counter;
			echo "<div class='NestedComments' style='margin-left: ".$margin."px'>";
		}

		echo "<p>".$record['username'].":<br>".$record['comment_text']."</p>";
		echo "<form>";
		echo "<input class='DeleteButton' value='D' type='button' id='".$record['username'].";".$record['comment_text']."' onclick='DeleteComment(this.id)'>";
		echo "<input class='SelectButton' value='S' type='button' id='".$record['username'].";".$record['comment_text']."' onclick='SelectComment(this.id)'>";

		//if we reach a max nesting level
		//turns off "Reply" button
		//instead of 5 can be MAX_COUNTER constant if need
		if($counter<5){
			echo "<input class='ReplyButton' value='R' type='button' id='".$record['comment_id']."' onclick='ReplyComment(this.id)'>";
		} else {
			echo "<input class='ReplyButton' value='R' type='button' id='".$record['comment_id']."''>";
		}
		echo "</form>";

		//Closing div for NestedComments class
		if($counter > 0){
			echo "</div>";
		}
		// recursively outputs comments
		// parameter = references for current comment
		// recursion factor = $counter
		if($counter < 5){
			$sql = "SELECT * FROM Comments WHERE reference_comment_id=".$record['comment_id'];
			$result = $current_connection->query($sql);
			if($result) { 
				Comment_Output($result,$counter+1,$current_connection);
			} else {
				return;
			}
		} else {
			return;
		}
	}
	
}
function DeletingNestedComments($comment_id,$current_connection){
	$sql = "SELECT comment_id FROM Comments where reference_comment_id='".$comment_id."'";
	$result = $current_connection->query($sql);

	if($result->num_rows>0){
		while($row = $result->fetch_assoc()){
			DeletingNestedComments($row['comment_id'],$current_connection);
		} 
	} else {
				$sql = "DELETE FROM Comments WHERE comment_id=".$comment_id;
				$current_connection->query($sql);
			}															//Amazing magic
	$sql = "DELETE FROM Comments WHERE comment_id=".$comment_id;
	$current_connection->query($sql);
}

$servername = "localhost";
$username = "root";
//Don't forget to write your root password to a server
//Or change username
$password = "gfdkbiby";
//Don't forget!!!!!!!!
$dbname = "HotelsTestTaskDB";

//Create connection
$conn = new mysqli($servername,$username,$password);

//Check connection
if($conn->connect_error) {
	die("Connection failed: " .$conn->connect_error);
}

//Selecting our db
$conn->select_db($dbname);

$q = $_GET['q'];
$user = $_GET['user'];
$comment = $_GET['comment'];
$reference_id = $_GET['reference_id'];


//Check our request
switch($q[0]) {

	//Insert record into database
	case 'I':

		$sql = "INSERT INTO Comments (username,comment_text)
				VALUES('" . $user . "' , '" . $comment ."')";

		$Succes_Message = "Record Inserted Succesfully";
		$Error_Message = "Error inserting record";
		
		if($conn->query($sql) === TRUE){
			echo $Succes_Message;
		} else {
			echo $Error_Message.$conn->error();
		}
		break;

	//Delete record from database
	case 'D':

		$sql = "SELECT comment_id FROM Comments 
				WHERE username='" . $user . "' AND comment_text='" . $comment ."'
				LIMIT 1;";
		$result = $conn->query($sql);

		if($result->num_rows>0){
		while($row = $result->fetch_assoc()){
			DeletingNestedComments($row['comment_id'],$conn);
		}
		}
		break;

	//Select record from database
	//Simple output on the page
	//But if needed we can do anything with result->fetch_assoc();
	case 'S':

		//Without LIMIT 1 we can select all same comments
		$sql = "SELECT username,comment_text FROM Comments 
				WHERE username='" . $user . "' AND comment_text='" . $comment ."' LIMIT 1 ";
		
		$result = $conn->query($sql);

		//Without LIMIT 1 this will works
		if($record = $result->fetch_assoc()){
			echo "You have selected:<br>".$record['username'].":<br>".$record['comment_text'];
		} else {
			echo "Error selecting record " . $conn->error();
		}
		break;

	//Reply
	//Inserting a new comment in database 
	//Inserting a reference in database
	case 'R':
		$sql = "INSERT INTO Comments (username,comment_text,reference_comment_id)
				VALUES('" . $user . "' , '" . $comment ."' , '" . $reference_id . "')";

		$Succes_Message = "Replying Comment record inserted succesfully";
		$Error_Message = "Error inserting replying comment record";
		
		if($conn->query($sql) === TRUE){
			echo $Succes_Message;
		} else {
			echo $Error_Message.$conn->error();
		}
		break;
}

//Select only level 0 comments
$sql = "SELECT * FROM Comments WHERE reference_comment_id IS NULL";

$result = $conn->query($sql);
Comment_Output($result,0,$conn);
?>
</body>
</html>