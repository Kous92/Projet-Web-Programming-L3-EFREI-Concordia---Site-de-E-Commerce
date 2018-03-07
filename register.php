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
	<title>Digital Phone: Register</title>
	<link rel="stylesheet" type="text/css" href="./CSS/register_style.css">
	<script type="text/javascript" src="./JS/time_date.js"></script>
</head>
<body>
	<header>
		<h1>Digital Phone</h1>
<?php

	if (isset($_SESSION['logged_in']))
	{
		echo '<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="products.php" target="_blank_">Products</a></li>
				<li><a href="cart.php" target="_blank_">Cart</a></li>';

		if (isset($_SESSION['user_type']))
		{
			if ($_SESSION['user_type'] === "admin")
			{
				echo '<li><a href="admin_part.php" target="_blank_">Administration</a></li>';
			}
		}	
	   	
	   	if (($_SESSION['logged_in'] === 0) || ($_SESSION['logged_in'] === 2))
	   	{
	   		echo '<li><a href="login.php" target="_blank_">Log in</a></li>
	   			  <li class="welcome">Welcome guest</li>
	   			</ul>
			</div>';
	   	}
	   	else
	   	{
	   		echo '<li><a href="logout.php">Log out</a></li>
				  <li class="welcome">Welcome ' . $_SESSION['user'] . '</li>
				</ul>
			</div>';
	   	}

	   	echo '<div id="errorbox1">
				<p>ERROR: You are already logged into the website. <a href="index.php" id="linkmenu">Click here to continue</a></p>
			  </div>
			<footer>
				<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>
		    </footer>';
		exit(); // Stop the execution of the rest of the code;
	}
	else
	{
		echo '<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="products.php" target="_blank_">Products</a></li>
				<li><a href="cart.php" target="_blank_">Cart</a></li>
	   			<li><a href="login.php" target="_blank_">Log in</a></li>
	   			<li class="welcome">Welcome guest</li>
	   		</ul>
		</div>';
	}
?>
	</header>

	<div class="register">
		<table class="registerbox">
			<form action="register.php" method="post">	

				<tr>
					<td colspan="2"><p>Please complete the registration form to create your account.<br>
									   The confirmation password must to be the same as password field.<br>
									   Fields with <sup id="required">*</sup> are required to fill</p></td>
				</tr>
		
				<tr>
					<td id="name">First name <sup id="required">*</sup></td>
					<td id="input"><input type="text" name="firstname" placeholder="Ex: John" required/><br></td>
				</tr>

				<tr>
					<td id="name">Last name <sup id="required">*</sup></td>
					<td id="input"><input type="text" name="lastname" placeholder="Ex: Doe" required/><br></td>
				</tr>

				<tr>
					<td id="name">Phone number <sup id="required">*</sup></td>
					<td id="input"><input type="tel" name="phone" placeholder="Ex: +1 514-523-9876" required/></td>
				</tr>

				<tr>
					<td id="name">Email adress <sup id="required">*</sup></td>
					<td id="input"><input type="email" name="email" placeholder="Ex: johndoe@example.com" required/><br></td>
				</tr>

				<tr>
					<td id="name">Password <sup id="required">*</sup></td>
					<td id="input"><input type="password" name="password" placeholder="6 characters minimum" required/></td>
				</tr>

				<tr>
					<td id="name">Confirm password <sup id="required">*</sup></td>
					<td id="input"><input type="password" name="password_confirm" placeholder="6 characters minimum (must be same)" required/></td>
				</tr>

				<tr>
					<td><br><input type="submit" value="Create account" title="Click on this button to create your account"/><br></td>
					<td><br>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
				</tr>
			</form>
		</table>
	</div>

<?php
	
	function add_user_to_database()
	{
		// Implemented soon...
	}

	function check_phone_number($phone_number)
	{
		// Worldwide phone number
		if (preg_match("#^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$#", $phone_number))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function check_first_name($first_name)
	{
		// Worldwide used characters for names only
		if (preg_match("/[0-9~!@#\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\.\<\>\?\/]+/", $first_name))
		{
			return false;
		}
		else
		{
			return true;
		}
		
		return true;
	}

	function check_last_name($last_name)
	{
		// Worldwide used characters for names only
		if (preg_match("/[0-9~!@#\$%\^&\*\(\)=\+\|\[\]\{\};\\:\",\.\<\>\?\/]+/", $last_name))
		{
			return false;
		}
		else
		{
			return true;
		}
		
		return true;
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

	function check_passwords($password, $password_confirm)
	{
		if ($password === $password_confirm)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Security check before adding an item into database
	if ((isset($_POST['phone'])) && (isset($_POST['firstname'])) && (isset($_POST['lastname'])) && (isset($_POST['email'])) && (isset($_POST['password'])) && (isset($_POST['password_confirm'])))
	{
		$phone_number = $_POST['phone']; // User phone number
		$first_name = $_POST['firstname']; // User First Name
		$last_name = $_POST['lastname']; // User Last Name
		$email = $_POST['email']; // User email
		$password = $_POST['password']; // The password
		$password_confirm = $_POST['password_confirm']; // The password to confirm

		// Security step to avoid XSS (Cross Site-Scripting) attacks with htmlspecialchars() function
		$phone_number = htmlspecialchars($phone_number);
		$first_name = htmlspecialchars($first_name);
		$last_name = htmlspecialchars($last_name);
		$email = htmlspecialchars($email);
		$password = htmlspecialchars($password);
		$password_confirm = htmlspecialchars($password_confirm);

		if (check_email($email) && check_passwords($password, $password_confirm) && check_phone_number($phone_number) && check_first_name($first_name) && check_last_name($last_name))
		{
			$user = new User();
			$user = $user->constructor($email, $first_name, $last_name, $phone_number, $password, 'regular');
			$user->insert_new_user();
		}
		else if (empty($email) || empty($password) || empty($password_confirm) || empty($phone_number) || empty($first_name) || empty($last_name))
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Form is incomplete. Please fill correctly the form<p>
				</div>";
		}
		else if (!check_email($email)) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid email format. Please complete again the form above.</p>
				</div>";
		}
		else if (!check_phone_number($phone_number)) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid phone number format. Please complete again the form above.</p>
				</div>";

		}
		else if (!check_first_name($first_name)) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid first name format. Please complete again the form above.</p>
				</div>";
		}
		else if (!check_last_name($last_name)) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid last name format. Please complete again the form above.</p>
				</div>";
		}
		else if (!check_passwords($password, $password_confirm)) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Passwords are different. Please complete again the form above.</p>
				</div>";
		}
		else if (strlen($password) < 6) 
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Password length is shorter than 6 characters. Please complete again the form above.</p>
				</div>";
		}
		else
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Form is incomplete. Please fill correctly the form.<p>
				</div>";
		}
	}
	else
	{
		echo "<div id=\"firsttrybox\">
					<p>Please fill correctly the form with your first name, your last name, your phone number,<br>
					   email address and same passwords. Click after on button to create your account.<p>
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