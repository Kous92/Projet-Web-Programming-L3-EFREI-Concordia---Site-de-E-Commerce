<?php
	if (!isset($_SESSION))
	{
	    session_start();
	}
	else
	{
		if (($_SESSION['logged_in'] === 0) || ($_SESSION['logged_in'] === 2))
		{
			session_destroy();
			session_start();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone</title>
	<link rel="stylesheet" type="text/css" href="./CSS/style.css">
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
	}
	else
	{
		echo '<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="products.php" target="_blank_">Products</a></li>
				<li><a href="cart.php" target="_blank_">Cart</a></li>
				<li><a href="register.php" target="_blank_">Register now</a></li>
	   			<li><a href="login.php" target="_blank_">Log in</a></li>
	   			<li class="welcome">Welcome guest</li>
	   		</ul>
		</div>';
	}
?>
	</header>
	
	<footer>
		<span id="time_date"></span>
    	<script type="text/javascript">window.onload = time_date('time_date');</script> &nbsp;&nbsp;&nbsp;
    	- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
    </footer>

</body>
</html>