<?php
/* For credit card fake information to simulate a payment with form of this source code:
   http://credit-card-generator.2-ee.com 
   376286554617499 */
   
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

	/*
	if (isset($_POST['emptyCart']))
	{
		$_SESSION['cart_item'] = null;
		setcookie('cart_item', "", time() - 3600); // Unset cookie (1 hour ago, before the actual time)
		$cart_item = null;
	}
	*/
	
	require_once("product_database.php");
	require_once("credit_card.php");

	function check_month($month)
	{
		// Check if the string have a month format with a regular expression
		if (preg_match("/^(0?[1-9]|1[012])$/", $month)) 
		{
		    return true;
		} 
		else 
		{
		    return false; // Invalid month
		}
	}

	function check_year($year)
	{
		// Check if the string have a year format with a regular expression (> 2000)
		if (preg_match("/^(20)\d{2}$/", $year)) 
		{
		    return true;
		} 
		else 
		{
		    return false; // Invalid year
		}
	}

	function check_cryptogram($cryptogram)
	{
		// Check if the string have a cryptogram format with a regular expression
		if (preg_match("/^[0-9]{3}$/", $cryptogram)) 
		{
		    return true;
		} 
		else 
		{
		    return false; // Invalid year
		}
	}

	// Checking if card number matches with card type
	function check_card_type($credit_card, $card_type)
	{
		if (preg_match("/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/", $credit_card))
		{
			$type = "Visa";
		}
		else if (preg_match("/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/", $credit_card))
		{
			$type = "MasterCard";
		}
		else if (preg_match("/^3[4,7]\d{13}$/", $credit_card))
		{
			$type = "American Express";
		}
		else
		{
			return false;
		}

		if ((strcmp($card_type, $type)) === 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function updateStock($item_ids, $item_ids_counter, $n)
	{
		/* Payment confirmed, we have to
		   - Update stock number */
		for ($i = 0; $i < $n; $i++)
		{
			if ($item_ids_counter[$i] > 0)
			{
				$current_product = new Product();
				$current_product->read_item($item_ids[$i]);
				$current_product->setProductStock($current_product->getProductStock() - $item_ids_counter[$i]);
				$current_product->update_stock($item_ids[$i]);
			}
		}
	}

	function send_email_receipt($email, $first_name, $item_ids, $item_ids_counter, $subtotal, $tax, $total_cost, $n, $credit_card, $card_type)
	{
		$credit_card = substr($credit_card, 0, 4) . " XXXX XXXX XXXX";

		for ($i = 0; $i < $n; $i++)
		{
			if ($item_ids_counter[$i] > 0)
			{
				$current_product = new Product();
				$current_product->read_item($item_ids[$i]);
				$current_product->itemToString($item_ids_counter[$i]);
			}
		}

		$body = "<b>Dear $first_name, thanks for ordering into Digital Phone online store</b>
		         <p>This confirmation email is your order receipt.</p>
		         <p></p>
		         <p></p>
		         <p>Your order details below:</p>";

		for ($i = 0; $i < $n; $i++)
		{
			if ($item_ids_counter[$i] > 0)
			{
				$current_product = new Product();
				$current_product->read_item($item_ids[$i]);
				$body = $body . $current_product->itemToString($item_ids_counter[$i]);
			}
		}

		$body = $body . "<p></p>
		                 <b>Subtotal: $subtotal</b>
		                 <p></p>
		                 <b>Canadian tax: $tax (15% of price)</b>
						 <p></p>
		                 <b>Total price: $total_cost</b>
		                 <p>Payment: $card_type $credit_card</p>
		                 <p></p>
		                 <b>Your order will be delivered at your house under 48 hours.</b>";

		$send = send_email($email, "Digital Phone: confirmation of your order", $body);

		if ($send)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone: Order payment</title>
	<link rel="stylesheet" type="text/css" href="./CSS/order.css">
	<script type="text/javascript" src="./JS/time_date.js"></script>
</head>
<body>
	<header>
		<h1>Digital Phone</h1>
<?php

	// For guests: access denied, page is locked !
	if (!isset($_SESSION['user_type']))
	{
		echo '<header>
				<h1>Digital Phone</h1>
				<div class="menu">
			        <ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="products.php" target="_blank_">Products</a></li>
						<li><a href="login.php" target="_blank_">Log in</a></li>
		   				<li class="welcome">Welcome guest</li>
		   			</ul>
		   		</div>
		      </header>

			  <div id="errorbox1">
				<p>ERROR: As a guest, you can\'t order your items. To order items, you must to be logged<br>
				   into the website, it will ease us to prepare the delivery of your order.<br><br>
				   If you already have a Digital Phone account, <a href=\'login.php\'> please log into your account here</a><br>
				   If you don\'t have yet a Digital Phone account, <a href=\'register.php\'> please register here</a></p>
			  </div>

			  <footer>
			  	<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>
		      </footer>';
		exit();
	}

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
					<form action="order.php" method="post">
			 <tr>
				<h3> ' . $_SESSION['user'] . ', proceed payment to order products on your cart</h3>
			  </tr>

			 	<tr>
					<td id="name">Credit Card Number <sup id="required">*</sup></td>
					<td colspan="2" id="input"><input type="text" class="creditcardnumber" name="creditcard" placeholder="Ex: 5111111111111111" maxlength="16" required/></td>
				</tr>

				<tr>
					<td id="name">Expiration date <sup id="required">*</sup></td>
					<td colspan="2"><input type="text" name="month" class="expiration" maxlength="2" placeholder="Ex: 06" required/>
					   &nbsp;/&nbsp;<input type="text" name="year" class="expiration"  maxlength="4" placeholder="Ex: 2018" required/></td>
				</tr>

				<tr>
					<td id="name">Cryptogram <sup id="required">*</sup></td>
					<td><input type="text" class="cryptogram" name="cryptogram" maxlength="3" placeholder="Ex: 111" required/></td>
				</tr>

				<tr>
					<td colspan="3"><br>Credit card type <sup id="required">*</sup>:<br></td>
			 	</tr>

				<tr>
					<td id="name"><input type="radio" name="card" value="mastercard" checked="checked"/>&nbsp;&nbsp;MasterCard</td>
					<td id="name"><input type="radio" name="card" value="visa"/>&nbsp;&nbsp;Visa</td>
					<td id="name"><input type="radio" name="card" value="amex"/>&nbsp;&nbsp;American Express</td>
				</tr>

				 <tr>
					<td><img src="./IMAGES/mastercard.gif"></td>
					<td><img src="./IMAGES/visa.gif"></td>
					<td><img src="./IMAGES/amex.gif"></td>
			  	</tr>';

		for ($i = 0; $i < $n; $i++)
		{
			if ($item_ids_counter[$i] > 0)
			{
				$current_product = new Product();
				$current_product->read_item($item_ids[$i]);
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
				<td colspan="3">Total to pay: ' . money_format('%i', ($total_cost + ($total_cost * $canadian_tax))) . '
			  </tr>

			  <tr>
				<td colspan="3"><br></td>
			  </tr>

			  <tr>
				<td><input type="submit" name="payment" value="Order and pay"></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
			  </tr>
			  
			      </form>
				</table>
			</div>';

		if ((isset($_POST['creditcard'])) && (isset($_POST['month'])) && (isset($_POST['year'])) && (isset($_POST['cryptogram'])) && (isset($_POST['card'])))
		{
			$credit_card = $_POST['creditcard']; // Credit card number
			$month = $_POST['month']; // Expiration date: month
			$year = $_POST['year']; // Expiration date: year
			$cryptogram = $_POST['cryptogram']; // Cryptogram

			// Security step to avoid XSS (Cross Site-Scripting) attacks with htmlspecialchars() function
			$credit_card = htmlspecialchars($credit_card);
			$month = htmlspecialchars($month);
			$year = htmlspecialchars($year);
			$cryptogram = htmlspecialchars($cryptogram);

			if ((check_month($month)) && (check_year($year)) && (check_cryptogram($cryptogram)))
			{
				// Final verification with card type by using fngccvalidator class (FNG Credit Card Validator library) 
				if ($_POST['card'] == 'mastercard')
				{
					if (check_card_type($credit_card, "MasterCard"))
					{
						$fngccvalidator = new fngccvalidator();
					
						if (($fngccvalidator->CreditCard($credit_card)) != false)
						{
							$result = array();
							$result = $fngccvalidator->CreditCard($credit_card, 'MasterCard');

							if (isset($_SESSION['user_email']))
							{
								setlocale(LC_MONETARY, 'fr_CA');
								if (send_email_receipt($_SESSION['user_email'], $_SESSION['user'], $item_ids, $item_ids_counter, money_format('%i', ($total_cost)), money_format('%i', ($total_cost * $canadian_tax)), money_format('%i', ($total_cost + ($total_cost * $canadian_tax))), $n, $credit_card, "MasterCard"))
								{
									updateStock($item_ids, $item_ids_counter, $n, $cart_item);
									echo "<div id=\"successbox\">
										<p>". $_SESSION['user'] .", thank you for your order. A confirmation email is sent as a receipt for your order.<br>
										   Your order will be delivered at your house under 48 hours.</p>
									</div>";
								}
								else
								{
									echo "<div id=\"errorbox\">
											<p>ERROR: There was a problem for sending the email to " . $_SESSION['user_email'] . "<p>
										  </div>";
								}
							}
							else
							{
								echo "<div id=\"errorbox\">
										<p>ERROR: Technical problem for your order, we apologize for the inconvenience.<p>
									  </div>";
							}
						}
						else
						{
							echo "<div id=\"errorbox\">
									<p>ERROR: Invalid credit card number. Please complete again the form above.</p>
								</div>";
						}
					}
					else
					{
						echo "<div id=\"errorbox\">
									<p>ERROR: Invalid credit card type. Please complete again the form above.</p>
								</div>";
					}
				}
				else if ($_POST['card'] == 'visa')
				{
					if (check_card_type($credit_card, "Visa"))
					{
						$fngccvalidator = new fngccvalidator();
					
						if (($fngccvalidator->CreditCard($credit_card)) != false)
						{
							$result = array();
							$result = $fngccvalidator->CreditCard($credit_card, 'Visa');

							if (isset($_SESSION['user_email']))
							{
								setlocale(LC_MONETARY, 'fr_CA');
								if (send_email_receipt($_SESSION['user_email'], $_SESSION['user'], $item_ids, $item_ids_counter, money_format('%i', ($total_cost)), money_format('%i', ($total_cost * $canadian_tax)), money_format('%i', ($total_cost + ($total_cost * $canadian_tax))), $n, $credit_card, "Visa"))
								{
									updateStock($item_ids, $item_ids_counter, $n);
									echo "<div id=\"successbox\">
										<p>". $_SESSION['user'] .", thank you for your order. A confirmation email is sent as a receipt for your order.<br>
										   Your order will be delivered at your house under 48 hours.</p>
									</div>";
								}
								else
								{
									echo "<div id=\"errorbox\">
											<p>ERROR: There was a problem for sending the email to " . $_SESSION['user_email'] . "<p>
										  </div>";
								}
							}
							else
							{
								echo "<div id=\"errorbox\">
										<p>ERROR: Technical problem for your order, we apologize for the inconvenience.<p>
									  </div>";
							}
						}
						else
						{
							echo "<div id=\"errorbox\">
									<p>ERROR: Invalid credit card number. Please complete again the form above.</p>
								</div>";
						}
					}
					else
					{
						echo "<div id=\"errorbox\">
									<p>ERROR: Invalid credit card type. Please complete again the form above.</p>
								</div>";
					}
				}
				else if ($_POST['card'] == 'amex') // American Express
				{
					if (check_card_type($credit_card, "American Express"))
					{
						$fngccvalidator = new fngccvalidator();
					
						if (($fngccvalidator->CreditCard($credit_card)) != false)
						{
							$result = array();
							$result = $fngccvalidator->CreditCard($credit_card, 'American Express');

							if (isset($_SESSION['user_email']))
							{
								setlocale(LC_MONETARY, 'fr_CA');
								if (send_email_receipt($_SESSION['user_email'], $_SESSION['user'], $item_ids, $item_ids_counter, money_format('%i', ($total_cost)), money_format('%i', ($total_cost * $canadian_tax)), money_format('%i', ($total_cost + ($total_cost * $canadian_tax))), $n, $credit_card, "American Express"))
								{
									updateStock($item_ids, $item_ids_counter, $n);
									echo "<div id=\"successbox\">
										<p>". $_SESSION['user'] .", thank you for your order. A confirmation email is sent as a receipt for your order.<br>
										   Your order will be delivered at your house under 48 hours.</p>
									</div>";
								}
								else
								{
									echo "<div id=\"errorbox\">
											<p>ERROR: There was a problem for sending the email to " . $_SESSION['user_email'] . "<p>
										  </div>";
								}
							}
							else
							{
								echo "<div id=\"errorbox\">
										<p>ERROR: Technical problem for your order, we apologize for the inconvenience.<p>
									  </div>";
							}
						}
						else
						{
							echo "<div id=\"errorbox\">
									<p>ERROR: Invalid credit card number. Please complete again the form above.</p>
								</div>";
						}
					}
					else
					{
						echo "<div id=\"errorbox\">
									<p>ERROR: Invalid credit card type. Please complete again the form above.</p>
								</div>";
					}
				}
				else
				{
					echo "<div id=\"errorbox\">
								<p>ERROR: Invalid credit card. Please complete again the form above.</p>
							</div>";
				}
			}
			else if (empty($month) || empty($year) || empty($cryptogram))
			{
				echo "<div id=\"errorbox\">
						<p>ERROR: Form is incomplete. Please fill correctly the form<p>
					</div>";
			}
			else if (!check_month($month)) 
			{
				echo "<div id=\"errorbox\">
						<p>ERROR: Invalid month format. Please complete again the form above.</p>
					</div>";
			}
			else if (!check_year($year)) 
			{
				echo "<div id=\"errorbox\">
						<p>ERROR: Invalid year format. Please complete again the form above.</p>
					</div>";

			}
			else if (!check_cryptogram($cryptogram)) 
			{
				echo "<div id=\"errorbox\">
						<p>ERROR: Invalid cryptogram format. Please complete again the form above.</p>
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
			echo "<div id=\"firsttrybox1\">
					<p>Please complete the form with your credit card informations (number, expiration date, <br>
					   cryptogram and credit card type) and click on button to pay your order.</p>
				</div>";
		}
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