<?php

	if (!isset($_SESSION))
	{
		session_start();
	}

	require_once("user_database.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone: Log in</title>
	<link rel="stylesheet" type="text/css" href="./CSS/login_style.css">
	<script type="text/javascript" src="./JS/time_date.js"></script>
</head>
<body>
	<header>
		<h1>Digital Phone</h1>
<?php	

	if ((!isset($_SESSION['logged_in'])) || (!isset($_SESSION['user_type'])))
	{
		echo '<div class="menu">
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="register.php" target="_blank_">Register now</a></li>	
				</ul>
			</div>';
	}
?>
	</header>
<?php	
	
	// 2 ways to check if a user is logged into the website
	if (isset($_SESSION['user_type']))
	{
		echo '<div id="errorbox">
				<p>ERROR: You are already logged into the website. <a href="index.php" id="linkmenu">Click here to continue</a></p>
			  </div>
			<footer>
				<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>
		    </footer>';
		exit(); // Stop the execution of the rest of the code;
	}
	else if (isset($_SESSION['user_email']))
	{
		echo '<div id="errorbox">
				<p>ERROR: You are already logged into the website. <a href="index.php" id="linkmenu">Click here to continue</a></p>
			  </div>
			<footer>
				<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>
		    </footer>';
		exit(); // Stop the execution of the rest of the code;
	}

	if (isset($_SESSION['logged_in']))
	{
		if ($_SESSION['logged_in'] === 1)
		{
			echo '<div id="errorbox">
				<p>ERROR: You are already logged into the website. <a href="index.php" id="linkmenu">Click here to continue</a></p>
			  </div>
			<footer>
				<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>
		    </footer>';
		}
		else
		{
			goto form;
		}

		exit();
	}
	else
	{
		form:
		echo '<div class="login">
			<p>Please complete the registration form to create your account<br>
			   Fields with <sup id="required">*</sup> are required to fill</p><br>
				<table class="loginbox">
					<form action="' . $_SERVER["PHP_SELF"] .'" method="post">	
						<tr>
							<td id="name">User email adress <sup id="required">*</sup></td>
							<td id="input"><input type="email" name="email" placeholder="Ex: johndoe@example.com" required/><br></td>
						</tr>

						<tr>
							<td id="name">Password <sup id="required">*</sup></td>
							<td id="input"><input type="password" name="password" placeholder="6 characters minimum" required/></td>
						</tr>

						<tr>
							<td><br><input type="submit" value="Log in" title="Click on this button to check your identifiers and log into your account"/></td>
							<td><br><input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
						</tr>
					</form>
				</table>
			</div>';
	}	

	function check_email($email)
	{
		// Check if the string have an email format with a regular expression
		if (preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) 
		{
		    return true;
		} 
		else 
		{
		    return false; // Invalid email
		}
	}

	// Security check before adding an item into database
	if ((isset($_POST['email'])) && (isset($_POST['password'])))
	{
		$email = $_POST['email']; // User email
		$password = $_POST['password']; // The password

		// Security step to avoid XSS (Cross Site-Scripting) attacks with htmlspecialchars() function
		$email = htmlspecialchars($email);
		$password = htmlspecialchars($password);

		if (check_email($email))
		{
			if (strlen($password) < 6)
			{
				echo "<div id=\"errorbox\">
						<p>ERROR: Password length is shorter than 6 characters. Please complete again the form above</p>
					</div>";
			}
			else
			{
				$user = new User();
				$user = $user->constructor_login($email, $password);
				$check = $user->validate_user();

				if ($check == 1)
				{
					$_SESSION['user'] = $user->getUserFirstName();
					$_SESSION['user_email'] = $user->getUserEmail();
					$_SESSION['user_type'] = $user->getUserType();
					$_SESSION['logged_in'] = 1;

					echo '<div id="successbox">
							<p>You are now logged in, welcome back ' .$user->getUserFirstName(). '. <a href="index.php" id="linkmenu">Click here to continue</a></p>
						  </div>';
				}
				else if ($check == 2)
				{
					echo "<div id=\"errorbox\">
						<p>ERROR: Invalid user name, no user with $email exists.<p>
					</div>";
				}
				else
				{
					echo "<div id=\"errorbox\">
						<p>ERROR: Incorrect password. Please complete again the form above</p>
					</div>";
				}
			}
		}
		else if ((!empty($email)) && (!check_email($email))) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid email format. Please complete again the form above</p>
				</div>";
		}
		else if (empty($email) || empty($password))
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Form is incomplete. Please fill correctly the form above<p>
				</div>";
		}
		else
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Form is incomplete. Please fill correctly the form.<p>
				</div>";
		}

		unset($_POST);
	}
	else
	{
		
		echo "<div id=\"firsttrybox\">
					<p>Please fill correctly the form with your email address and associated password from your account<p>
				</div>";
	}
?>

	<footer>
		<span id="time_date"></span>
    	<script type="text/javascript">window.onload = time_date('time_date');</script>&nbsp;&nbsp;&nbsp;
    	- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
    </footer>

</body>
</html>