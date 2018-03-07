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

	require_once("product_database.php");

	// Set shopping cart items
	$cart_item = null;
	
	if (isset($_SESSION['cart_item']))
	{
		$cart_item = $_SESSION['cart_item'];
	}
	else if (isset($_COOKIE['cart_item']))
	{
		$cart_item = $_COOKIE['cart_item'];
	}

	
	if (isset($_POST['productID']))
	{
		$productToAdd = $_POST['productID'];
		$expiration = time() + (60 * 30); // 30 minutes

		// Add cart item to cookie			
		if (isset($_COOKIE['cart_item']))
		{
			// Append to existing cookie
			$cart_item = $_COOKIE['cart_item'];
			$cart_item = "$cart_item,$productToAdd";
		}
		else
		{
			// Create cookie
			$cart_item = $productToAdd;
		}


		// Add cart item to session
		if (isset($_SESSION['cart_item']))
		{
			$cart_item = $_SESSION['cart_item'];
			$cart_item = "$cart_item,$productToAdd";	
		}
		else
		{
			$cart_item = "$productToAdd";
		}

		$_SESSION['cart_item'] = $cart_item;
		setcookie("cart_item", $cart_item, $expiration);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone: Products</title>
	<link rel="stylesheet" type="text/css" href="./CSS/product.css">
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
				<li><a href="cart.php" target="_blank_">Cart</a></li>
				<li><a href="register.php" target="_blank_">Register now</a></li>
	   			<li><a href="login.php" target="_blank_">Log in</a></li>
	   			<li class="welcome">Welcome guest</li>
	   		</ul>
		</div>';
	}
?>
	</header>
<?php
	$results_id = retrieve_products($_SESSION['id_utilisateur']);

	while ($row = $results_id->fetch_assoc())
	{
		$brand = $row['productBrand'];
		$model = $row['productModel'];
		$memory = $row['productMemory'];
		$stock = $row['productStock']; 
		$cost = $row['productCost'];
		$description = $row['productDescription'];
	}

	display_all_products($brand, $model, $memory, $stock, $cost, $description);

	if (mysqli_num_rows($results_id) < 8)
	{
		echo '<footer class="v1">
				<span id="time_date"></span>
			    <script type="text/javascript">window.onload = time_date(\'time_date\');</script>&nbsp;&nbsp;&nbsp;
			    - &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
			</footer>'; 
	}
	else
	{
		echo '<footer>
				<span id="time_date"></span>
			    <script type="text/javascript">window.onload = time_date(\'time_date\');</script>&nbsp;&nbsp;&nbsp;
			    - &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
			  </footer>'; 
	}
?>

</body>
</html>