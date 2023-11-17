<?php

session_start();

if(isset($_SESSION["user_id"]))
{
	header("location:home.php");
}

?>



<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com-->
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="http://code.jquery.com/jquery.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title> Registration or Sign Up form in HTML CSS | CodingLab </title>
    <link rel="stylesheet" href="css/style.css">
   </head>
<body>
<?php
			if(isset($_GET["register"]))
			{
				if($_GET["register"] == 'success')
				{
					echo '<script>alert("Registerd Successfully!")</script>';
				}
			}
			?>

  <div class="wrapper">
    <h2>Login</h2>
    <form method="POST" id="login_form">
      <div class="input-box" id="email_area">
        <input type="text" placeholder="Enter your email" name="user_email" id="user_email">
        <span id="user_email_error" class="text-danger"></span>
      </div>
      <div class="input-box" id="password_area" style="display:none;">
        <input type="password" placeholder="Enter your password" name="user_password" id="user_password">
        <span id="user_password_error" class="text-danger"></span>
      </div>
      <div class="input-box" id="otp_area" style="display:none;">
        <input type="text" placeholder="Enter your OTP" name="user_otp" id="user_otp">
        <span id="user_otp_error" class="text-danger"></span>
      </div>
      
    
      <div class="input-box button">
        
        <input type="hidden" name="action" id="action" value="email" />
	    <input type="submit" name="next" id="next" value="Next" />
      </div>
	  <div class="text">
        <h3>Remember Your Password? <a href="forget.php?step1">Forget Password</a></h>
      </div>

    </form>
  </div>
	


  <script>

$(document).ready(function(){
	$('#login_form').on('submit', function(event){
		event.preventDefault();
		var action = $('#action').val();
		$.ajax({
			url:"login_verify.php",
			method:"POST",
			data:$(this).serialize(),
			dataType:"json",
			beforeSend:function()
			{
				$('#next').attr('disabled', 'disabled');
			},
			success:function(data)
			{
				$('#next').attr('disabled', false);
				if(action == 'email')
				{
					if(data.error != '')
					{
						$('#user_email_error').text(data.error);
						
					}
					else
					{
						$('#user_email_error').text('');
						$('#email_area').css('display', 'none');
						$('#password_area').css('display', 'block');
					}
				}
				else if(action == 'password')
				{
					if(data.error != '')
					{
						$('#user_password_error').text(data.error);
					}
					else
					{
						$('#user_password_error').text('');
						$('#password_area').css('display', 'none');
						$('#otp_area').css('display', 'block');
					}
				}
				else
				{
					if(data.error != '')
					{
						$('#user_otp_error').text(data.error);
					}
					else
					{
						window.location.replace("home.php");
					}
				}

				$('#action').val(data.next_action);
			}
		})
	});
});

</script>


</body>
</html>

