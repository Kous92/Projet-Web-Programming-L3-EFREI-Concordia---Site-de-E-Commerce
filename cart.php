<?php
	if (!isset($_SESSION))
	{
	    session_start();
	}

	$cart_item = null;

	if (isset($_SESSION['cart_item']))
	{
		$cart_item = $_SESSION['cart_item'];
	}
	else if (isset($_COOKIE['cart_item']))
	{
		if (!isset($_POST['emptyCart']))
		{
			$cart_item = $_COOKIE['cart_item'];
		}
	}

	if (isset($_POST['emptyCart']))
	{
		$_SESSION['cart_item'] = null;
		setcookie('cart_item', "", time() - 3600); // Unset cookie (1 hour ago, before the actual time)
		$cart_item = null;
	}
	
	require_once("product_database.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone: Cart</title>
	<link rel="stylesheet" type="text/css" href="./CSS/cart.css">
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
				<li><a href="products.php" target="_blank_">Products</a></li>';

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
				<li><a href="register.php" target="_blank_">Register now</a></li>
	   			<li><a href="login.php" target="_blank_">Log in</a></li>
	   			<li class="welcome">Welcome guest</li>
	   		</ul>
		</div>';
	}

?>
	</header>
<?php

	if (isset($cart_item))
	{
		$currency = "$" . "CA"; // Canadian dollar currency
		$splitted_cart = explode(",", $cart_item);
		$item_ids = array();
		$item_ids_counter = array();
		$current_id = 0;
		$new_id = 0;
		$n = 0;
		$canadian_tax = 0.15;

		$item_costs = array();
		$total_cost = 0;
		$total_items = 0;

		$product = new Product();
		$n = $product->getLastID();

		for ($i = 0; $i < $n; $i++)
		{
			$item_ids[$i] = $i + 1;
			$item_ids_counter[$i] = 0;
			$item_costs[$i] = 0;
		}

		// Initialize products
		foreach ($splitted_cart as $item_id)
		{
			// To avoid fatal error with read_item function (lowest ID number is 1, NOT 0)
			if ($item_id > 0)
			{
				$current_product = new Product();
				$current_product->read_item($item_id);
				$item_ids_counter[$item_id - 1]++;
				$item_costs[$item_id - 1] = $current_product->getProductCost();
			}
		}

		echo '<div class="cart">
				<table class="itemcart">
					<form action="cart.php" method="post">
			 <tr>
				<td colspan="3" class="title"><h2>Your shopping cart</h2></td>
			  </tr>

			 <tr>
				<td colspan="3"><br></td>
			  </tr>


			 <tr>
				<th>Item name</th>
				<th>Unit price</th>
				<th class="item_quantity">Amount</th>
			  </tr>';

		for ($i = 0; $i < $n; $i++)
		{
			if ($item_ids_counter[$i] > 0)
			{
				$current_product = new Product();
				$current_product->read_item($item_ids[$i]);
				$current_product->displayInCart($item_ids_counter[$i]);
			}
		}

		// Calculate the total price and total items on cart
		for ($i = 0; $i < $n; $i++)
		{
			if ($item_ids_counter[$i] > 0)
			{
				$total_items = $total_items + $item_ids_counter[$i];
				$total_cost = $total_cost + ($item_ids_counter[$i] * $item_costs[$i]);
			}
		}

		// Print the international format for the fr_CA locale
		setlocale(LC_MONETARY, 'fr_CA');

		echo '<tr>
				<td colspan="3"><br></td>
			  </tr>

			  <tr>
				<td colspan="3">Total items: ' . $total_items . '</td>
			  </tr>

			  <tr>
				<td colspan="3">Subtotal: ' . money_format('%i', ($total_cost)) . '
			  </tr>

			  <tr>
				<td colspan="3">Canadian tax: ' . money_format('%i', ($total_cost * $canadian_tax)) . ' (15% of subtotal)</td>
			  </tr>

			  <tr>
				<td colspan="3">Total: ' . money_format('%i', ($total_cost + ($total_cost * $canadian_tax))) . '
			  </tr>

			  <tr>
				<td colspan="3"><br></td>
			  </tr>

			  <tr>
				<td><input type="submit" name="emptyCart" value="Empty Cart"></form></td>
				<td><form action="products.php" method="post"><input type="submit" value="Continue shoping"></form></td>
				<td><form action="order.php" method="post"><input type="submit" value="Proceed payment"></form></td>
			  </tr>
				</table>
			</div>';
	}
	else
	{
		echo "<div id=\"firsttrybox\">
					<p>No product in shopping cart, <a href='products.php' id='shopping'> please continue your shopping here</a></p>
				</div>
			<footer>
				<span id=\"time_date\"></span>
		    	<script type=\"text/javascript\">window.onload = time_date('time_date');</script>&nbsp;&nbsp;&nbsp;
    			- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
		    </footer>";
	}

?>

</body>
</html>