<?php

//email_verify.php

$connect = new PDO("mysql:host=localhost;dbname=testing", "root", "");

$error_user_otp = '';
$user_activation_code = '';
$message = '';

if(isset($_GET["code"]))
{
	$user_activation_code = $_GET["code"];

	if(isset($_POST["submit"]))
	{
		if(empty($_POST["user_otp"]))
		{
			$error_user_otp = 'Enter OTP Number';
		}
		else
		{
			$query = "
			SELECT * FROM register_user 
			WHERE user_activation_code = '".$user_activation_code."' 
			AND user_otp = '".trim($_POST["user_otp"])."'
			";

			$statement = $connect->prepare($query);

			$statement->execute();

			$total_row = $statement->rowCount();

			if($total_row > 0)
			{
				$query = "
				UPDATE register_user 
				SET user_email_status = 'verified' 
				WHERE user_activation_code = '".$user_activation_code."'
				";

				$statement = $connect->prepare($query);

				if($statement->execute())
				{
					header('location:login.php?register=success');
				}
			}
			else
			{
				$message = '<label class="text-danger">Invalid OTP Number</label>';
			}
		}
	}
}
else
{
	$message = '<label class="text-danger">Invalid Url</label>';
}

?>





<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Verification | CodingLab </title>
    <link rel="stylesheet" href="css/style.css">
   </head>
<body>
  <div class="wrapper">
    <h2>
        Verify Registration!
    </h2>
    <?php echo $message; ?>
    <form method="post">
      
      <div class="input-box">
        <input type="text" placeholder="Enter Your OTP" name="user_otp">
        <?php echo $error_user_otp; ?>
      </div>
      <div class="input-box button">
        <input type="submit" name="submit" value="Verify">
      </div>
      <div class="text">
        <h3>Resend Your Email OTP! <a href="resend.php">Resend</a></h3>
      </div>
    </form>
  </div>

</body>
</html>
