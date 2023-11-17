<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//resend_email_otp.php

include('database_connection.php');

$message = '';

session_start();



if(isset($_SESSION["user_id"]))
{
	header("location:home.php");
}

if(isset($_POST["resend"]))
{
	if(empty($_POST["user_email"]))
	{
		$message = '<div class="alert alert-danger">Email Address is required</div>';
	}
	else
	{
		$data = array(
			':user_email'	=>	trim($_POST["user_email"])
		);

		$query = "
		SELECT * FROM register_user 
		WHERE user_email = :user_email
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		if($statement->rowCount() > 0)
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				if($row["user_email_status"] == 'verified')
				{
					$message = '<div class="alert alert-info">Email Address already verified, you can login into system</div>';
				}
				else
				{
					
					require 'PHPMailer/src/Exception.php';
			        require 'PHPMailer/src/PHPMailer.php';
			        require 'PHPMailer/src/SMTP.php';
			        require 'PHPMailer/src/POP3.php';

					$mail = new PHPMailer(true);
					$mail->IsSMTP();
					$mail->Host = 'smtp.gmail.com';
					$mail->Port = '465';
					$mail->SMTPAuth = true;
					$mail->Username = 'j7467920@gmail.com';					
					$mail->Password = 'facpjjybdvaiybrg';
					$mail->SMTPSecure = 'ssl';
					$mail->From = 'j7467920@gmail.com';
					$mail->FromName = 'Webslesson';
					$mail->AddAddress($row["user_email"]);
					$mail->WordWrap = 50;
					$mail->IsHTML(true);
					$mail->Subject = 'Verification code for Verify Your Email Address';
					$message_body = '
					<p>For verify your email address, enter this verification code when prompted: <b>'.$row["user_otp"].'</b>.</p>
					<p>Sincerely,</p>
					';
					$mail->Body = $message_body;

					if($mail->Send())
					{
						echo '<script>alert("Please Check Your Email for Verification Code")</script>';
						echo '<script>window.location.replace("register_verify.php?code='.$row["user_activation_code"].'");</script>';
					}
					else
					{

					}
				}
			}
		}
		else
		{
			$message = '<div class="alert alert-danger">Email Address not found in our record</div>';
		}
	}
}

?>



<!DOCTYPE html>
<!-- Coding by CodingLab | www.codinglabweb.com-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registration or Sign Up form in HTML CSS | CodingLab </title>
    <link rel="stylesheet" href="css/style.css">
   </head>
<body>
  <div class="wrapper">
    <h2>Resend Email</h2>
    <?php echo $message; ?>
    <form method="post">

      <div class="input-box">
        <input type="email" placeholder="Enter your Email" name="user_email">
        
      </div>
      
      <div class="input-box button">
        <input type="Submit" name="resend"  value="Send">
      </div>
      
    </form>
  </div>

</body>
</html>
