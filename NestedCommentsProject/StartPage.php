<!DOCTYPE html>
<html>
<head>
	<title> Main page </title>
	<link rel="stylesheet" href="HotelsTestTask.css">
	<meta charset="utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>
	//Searching for empty strings
	//And for whitespaces
	function Validation(username,comment){
		if(!Boolean(username.trim())){
				alert("sorry,UserName cannot be empty");
				return false;
			}
		if(!Boolean(comment.trim())){
				alert("sorry,Comment cannot be empty");
				return false;
			}
		return true;
	}

	//Refreshing nested comments view
	function NestedCommentsHandler(){
		var indicator = document.getElementById("NestedComments");
		var NestedComments = document.getElementsByClassName("NestedComments");

		for(var i=0;i<NestedComments.length;i++){
			if(indicator.checked){
				NestedComments[i].style.display = "block";
			} else {
				NestedComments[i].style.display = "none";
			}
		} 
	}

	function AddComment(){
		var username = document.getElementById('Owner').value;
		var comment_text = document.getElementById('Comment').value;  //Adding new comment from form
		Handler('I',username,comment_text,"");
	}

	function DeleteComment(temp_str){
		var param = temp_str.split(';');   	//Deleting comment by user name and text in comment
		Handler('D',param[0],param[1],"");
	}

	function SelectComment(temp_str){
		var param = temp_str.split(';');   //Selecting comment by user name and text in comment
		Handler('S',param[0],param[1],"");
	}

	function ReplyComment(temp_str){
		
		var username = prompt("Enter your name");
		var comment_text = prompt("Enter your comment");  //insert a nested comment on database
		Handler('R',username,comment_text,temp_str);	  // and insert a reference to another comment
	}

	//Forming async xml-request to our script
	function Handler(str,UserName,Comment_Text,reference_id){
			if(!Validation(UserName,Comment_Text)) {alert("Bye");return;}
			if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            var xmlhttp = new XMLHttpRequest();
        	} else {
            // code for IE6, IE5
            var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        	}
			xmlhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200){
					document.getElementById('CommentSection').innerHTML = this.responseText;
					NestedCommentsHandler();                                                // refresh nested comment wiew when get response
				}
			};
			alert(str+" "+UserName+" "+Comment_Text);
			if(!Boolean(reference_id.trim())){
				xmlhttp.open("GET","CommentsHandler.php?q="+str+"&user="+UserName+"&comment="+Comment_Text,true);
			} else {
				xmlhttp.open("GET","CommentsHandler.php?q="+str+"&user="+UserName+"&comment="+Comment_Text+"&reference_id="+reference_id,true);	
			}
			xmlhttp.send();
	}

	//jquery to make checkbox checked when reload
	$(function(){
		if(localStorage.input === 'true'){
			var test = false;
		} else {
			var test = true;
		}
		$('input').prop('checked',test || false);
	});

	$('input').on('change',function(){
		localStorage.input = $(this).is(':checked');
		console.log($(this).is(':checked'));
	});
	</script>
</head>
<body>
<form>
 	<label>
 		Show nested comments
 		<input type="checkbox" id="NestedComments" onchange="NestedCommentsHandler();" onload="document.getElementById("NestedComments").checked = true;" checked>
	</label>
	<br>
	<label>
		Enter Your Name:
		<input type="text" id="Owner">
	</label>	
	<br>
	<label>
		Enter your comment:
		<input type="text" id="Comment">
	</label>
	<input class="AddButton" type="button" value="Add Comment" onclick="AddComment()" > 

</form>
<div id = "CommentSection">


<?php
//Output function
//Needed,when something change in db without ajax-request from static page
//Or to load actual comment page view when page is just opened
function Comment_Output($Comment_array,$counter,$current_connection){
	while($record = $Comment_array->fetch_assoc()) {
		if($counter>0){
			$margin = 30*$counter;
			echo "<div class='NestedComments' style='margin-left: ".$margin."px'>";
		}
		echo "<p>".$record['username'].":<br>".$record['comment_text']."</p>";
		echo "<form>";
		echo "<input class='DeleteButton' value='D' type='button' id='".$record['username'].";".$record['comment_text']."' onclick='DeleteComment(this.id)'>";
		echo "<input class='SelectButton' value='S' type='button' id='".$record['username'].";".$record['comment_text']."' onclick='SelectComment(this.id)'>";
		if($counter<5){
			echo "<input class='ReplyButton' value='R' type='button' id='".$record['comment_id']."' onclick='ReplyComment(this.id)'>";
		} else {
			echo "<input class='ReplyButton' value='R' type='button' id='".$record['comment_id']."''>";
		}
		echo "</form>";
		if($counter > 0){
			echo "</div>";
		}
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
$servername = "localhost";
$username = "root";
//Don't forget to write your root password to a server
//Or change username
$password = "";
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

$sql = "SELECT * FROM Comments WHERE reference_comment_id IS NULL";

$result = $conn->query($sql);
Comment_Output($result,0,$conn); 
?>
</div>
</body>
</html>