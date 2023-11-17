<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//index.php

//error_reporting(E_ALL);

session_start();

if(isset($_SESSION["user_id"]))
{
	header("location:home.php");
}

//include('function.php'); 


$connect = new PDO("mysql:host=localhost; dbname=testing", "root", "");

$message = '';
$error_user_name = '';
$error_user_email = '';
$error_user_password = '';
$user_name = '';
$user_email = '';
$user_password = '';

if(isset($_POST["register"]))
{
	if(empty($_POST["user_name"]))
	{
		$error_user_name = "<label class='text-danger'>Enter Name</label>";
	}
	else
	{
		$user_name = trim($_POST["user_name"]);
		$user_name = htmlentities($user_name);
	}

	if(empty($_POST["user_email"]))
	{
		$error_user_email = '<label class="text-danger">Enter Email Address</label>';
	}
	else
	{
		$user_email = trim($_POST["user_email"]);
		if(!filter_var($user_email, FILTER_VALIDATE_EMAIL))
		{
			$error_user_email = '<label class="text-danger">Enter Valid Email Address</label>';
		}
	}

	if(empty($_POST["user_password"]))
	{
		$error_user_password = '<label class="text-danger">Enter Password</label>';
	}
	else
	{
		$user_password = trim($_POST["user_password"]);
		$user_password = password_hash($user_password, PASSWORD_DEFAULT);
	}

	if($error_user_name == '' && $error_user_email == '' && $error_user_password == '')
	{
		$user_activation_code = md5(rand());

		$user_otp = rand(100000, 999999);

		$data = array(
			':user_name'		=>	$user_name,
			':user_email'		=>	$user_email,
			':user_password'	=>	$user_password,
			':user_activation_code' => $user_activation_code,
			':user_email_status'=>	'not verified',
			':user_otp'			=>	$user_otp
		);

		$query = "
		INSERT INTO register_user 
		(user_name, user_email, user_password, user_activation_code, user_email_status, user_otp)
		SELECT * FROM (SELECT :user_name, :user_email, :user_password, :user_activation_code, :user_email_status, :user_otp) AS tmp
		WHERE NOT EXISTS (
		    SELECT user_email FROM register_user WHERE user_email = :user_email
		) LIMIT 1
		";

		$statement = $connect->prepare($query);

		$statement->execute($data);

		if($connect->lastInsertId() == 0)
		{
			$message = '<label class="text-danger">Email Already Register</label>';
		}	
		else
		{
	/* 
			$user_avatar = make_avatar(strtoupper($user_name[0]));

			$query = "
			UPDATE register_user 
			SET user_avatar = '".$user_avatar."' 
			WHERE register_user_id = '".$connect->lastInsertId()."'
			";
		 
			$statement = $connect->prepare($query);
		 
			$statement->execute(); */

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
			$mail->FromName = 'DigiBolt';
			$mail->AddAddress($user_email);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = 'Verification code for Verify Your Email Address';

			$message_body = '
            <i>Hello, '.$user_name.' Welcome To DigiBolt!<i><br>
			<p>For verify your email address, enter this verification code when prompted: <b>'.$user_otp.'</b>.</p>
			<p>Sincerely,</p>
			

			
			';
			$mail->Body = $message_body;

			if($mail->Send())
			{
				echo '<script>alert("Please Check Your Email for Verification Code")</script>';

				header('location:register_verify.php?code='.$user_activation_code);
			}
			else
			{
				$message = $mail->ErrorInfo;
			}
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
    <h2>Registration</h2>
    <?php echo $message; ?>
    <form method="post">
      <div class="input-box">
        <input type="text" placeholder="Enter your name" name="user_name">
        <?php echo $error_user_name; ?>
      </div>
      <div class="input-box">
        <input type="text" placeholder="Enter your email" name="user_email">
        <?php echo $error_user_email; ?>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Create password" name="user_password">
        <?php echo $error_user_password; ?>
      </div>
      <div class="policy">
        <input type="checkbox">
        <h3>I accept all terms & condition</h3>
      </div>
      <div class="input-box button">
        <input type="Submit" name="register" value="Register Now">
      </div>
	  <div class="text">
        <h3>Resend Verification Code? <a href="resend.php">Resend</a></h3>
      </div>
      <div class="text">
        <h3>Already have an account? <a href="login.php">Login</a></h3>
      </div>
      
    </form>
  </div>

</body>
</html>
