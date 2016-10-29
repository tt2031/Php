<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Registration</title>
		<link rel="stylesheet" href="css/php_task.css">
		<script src="js/Validation.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
    			$("input").change(function(){
					Validation($(this).get(0));
				});
				$("#registration_form").submit(function(e){
					if(!FinalCheck($(this).get(0))){
						e.preventDefault();
						return;
					}
					var url = "request_handler.php";
					$.ajax({
						type: "POST",
						url: url,
						data: $("#registration_form").serialize(),
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
		<form id="registration_form" action="request_handler.php" method="post">
      
        <h1>Sign Up</h1>
        
          <label>Name:</label>
          <input type="text" id="name" name="name">
          <p class="instruction">
          Login must be 3-12 characters and can contain only digits and latin characters.
          </p>
          
          <label>Email:</label>
          <input type="email" id="mail" name="email">
          
          <label>Password:</label>
          <input type="password" id="password" name="password">
          <p class="instruction">
          Password must be 6-15 characters and can contain only digits and latin characters.
          </p>
          <br>    
        <input type="submit" id="submit_button" value="Sign Up">
      </form>
      <a href="authentification.php" class="button">Log In</a>
	</body>
</html>