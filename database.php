<?php
  	require_once("PHPMailer/PHPMailerAutoload.php");

	function query_database($SQLCommand)
	{
		// Connection to database
		$mysqli = new mysqli('localhost', 'root', 'root', 'final_project_php_website');

		if ($mysqli->connect_errno)
		{
			die("<p id=\"error\">Error connecting to the database !</p>");
		}
		else
		{
			// echo "<p id=\"success\">Querying database...</p><br>";
		}

		$results_id = $mysqli->query($SQLCommand);
		$mysqli->close();

		return $results_id; // Returning the result of request (mysqli type)
	}

	function send_email($to, $subject, $body) 
	{
		global $error;
		$mail = new PHPMailer();  // create a new object

		// USE YOUR OWN CREDENTIAL BELOW...
		// - If you are going to put this file on github,
		//   you should create yourself a dummy email address at gmail
		// - You will need to verify by sms your email to be able to
		//   use Gmail's SMTP server
		$from_name = "Digital Phone";
		$mail->Username = "onlinestore.project.concordia@gmail.com";  
		$mail->Password = "ConcordiaUniversity";           

		$mail->IsHTML(true);
		$mail->IsSMTP(); // enable SMTP
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465; 
		$mail->SetFrom($mail->Username, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);


		if (!$mail->Send()) 
		{
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return false;
		} 
		else 
		{
			$error = 'Message sent!';
			return true;
		}
	}
?>