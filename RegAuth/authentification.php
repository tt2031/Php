<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Authentification</title>
		<link rel="stylesheet" href="css/php_task.css">
		<script src="js/Validation.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
    			$("input").change(function(){
					Validation($(this).get(0));
				});
				$("#auth_form").submit(function(e){
					if(!FinalCheck($(this).get(0))){
						e.preventDefault();
						return;
					}
					var url = "auth_handler.php";
					$.ajax({
						type: "POST",
						url: url,
						data: $("#auth_form").serialize(),
						success: function(data)
						{
							alert(data);
						}
					});
					e.preventDefault();
				});
			});
		</script>
	</head>
	<body>
	<form id="auth_form" action="auth_handler.php" method="post">
      
        <h1>Log In</h1>
        
          <label>Name:</label>
          <input type="text" id="name" name="name">
          
          <label>Password:</label>
          <input type="password" id="password" name="password">
          
          <br>    
        <input type="submit" id="submit_button" value="Log In">
      </form>
	</body>
</html>