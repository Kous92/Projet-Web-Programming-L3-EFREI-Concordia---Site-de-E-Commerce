<?php
	
	if (!isset($_SESSION))
	{
	    session_start();
	}
	
	require_once("product_database.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone: Admin part</title>
	<link rel="stylesheet" type="text/css" href="./CSS/adder.css">
	<script type="text/javascript" src="./JS/time_date.js"></script>
</head>
<body>
<?php

	// For malicious guests: access denied because a guest is not an administrator, page is locked !
	if (!isset($_SESSION['user_type']))
	{
		echo '<header>
		        <h1>Digital Phone</h1>
		      </header>
			  <div id="errorbox">
				<p>ERROR: ACCESS DENIED ! <a href="index.php" id="menu">Click here to continue</a></p>
			  </div>
			  <footer>
			  	<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>&nbsp;&nbsp;&nbsp;
    			- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
		      </footer>';
		exit(); // Stop the execution of the rest of the code;
	}

	// For malicious regular users: access denied because the user is not an administrator, page is locked !
	if (isset($_SESSION['user_type']))
	{
		if ($_SESSION['user_type'] !== "admin")
		{
			echo '<header>
		        <h1>Digital Phone</h1>
		      </header>
			  <div id="errorbox">
				<p>ERROR: ACCESS DENIED ! <a href="index.php" id="menu">Click here to continue</a></p>
			  </div>
			  <footer>
			  	<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>&nbsp;&nbsp;&nbsp;
    			- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
		      </footer>';
			exit(); // Stop the execution of the rest of the code;
		}
	}
?>
	<header>
		<h1>Digital Phone: Add item</h1>
		<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin_part.php" target="_blank_">Administration</a></li>
				<li><a href="admin_item_deleter.php" target="_blank_">Item deleter</a></li>
				<li><a href="admin_item_updater.php" target="_blank_">Item updater</a></li>	
				<li><a href="inventory.php" target="_blank_">Inventory</a></li>	
	<?php
	   	  echo '<li><a href="logout.php">Log out</a></li>
				<li class="welcome">Welcome ' . $_SESSION['user'] . '</li>
			</ul>
		</div>';
	?>
			</ul>
		</div>
	</header>

	<div class="item_manager">
		<p>Fill the form to add an item in database.<br>
	   	   Fields with <sup id="required">*</sup> are required to fill</p><br>

		<table class="itembox">
			<form action="admin_item_adder.php" method="post">	
				<tr>
					<td id="name"><label for="brand">Smartphone brand<sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="brand" placeholder="Ex: Apple" id="brand" required><br></td>
				</tr>

				<tr>
					<td id="name"><label for="model">Smartphone model <sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="model" placeholder="Ex: iPhone" id="model" required><br></td>
				</tr>

				<tr>
					<td id="name"><label for="memory">Memory size (GB)<sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="memory" placeholder="Ex: 16" id="memory" required pattern="[0-9]*"><br></td>
				</tr>

				<tr>
					<td id="name"><label for="stock">Units (in stock) <sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="stock" placeholder="Ex: 100" id="stock" required pattern="[0-9]*"><br></td>
				</tr>

				<tr>
					<td id="name"><label for="cost">Smartphone price ($) <sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="cost" placeholder="Ex: 399.99" id="cost" required pattern="[0-9]+([\.,][0-9]+)?"><br></td>
				</tr>

				<tr>
					<td id="name"><label for="description">Description </label></td>
					<td id="input"><textarea name="description" id="description" rows="2" cols="26"></textarea></td>
				</tr>

				<tr>
					<td><input type="submit" value="Add item" title="Click on this button to add an item in database"/></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
				</tr>
			</form>
		</table>
	</div>
		
<?php
    // Security check before adding an item into database
	if ((isset($_POST['brand'])) && (isset($_POST['model'])) && (isset($_POST['memory'])) && (isset($_POST['stock'])) && (isset($_POST['cost'])) && (isset($_POST['description'])))
	{
		$phone_brand = $_POST['brand']; // Item brand
		$phone_model = $_POST['model']; // Item model
		$phone_memory = $_POST['memory']; // In grams
		$phone_stock = $_POST['stock']; // Items in stock
		$phone_cost = $_POST['cost']; // Item price
		$phone_description = $_POST['description']; // Item description


		// Security to avoid XSS (Cross Site-Scripting) attacks
		$phone_brand = htmlspecialchars($phone_brand); // Item brand
		$phone_model = htmlspecialchars($phone_model); // Item model
		$phone_memory = htmlspecialchars($phone_memory); // In grams
		$phone_stock = htmlspecialchars($phone_stock); // Items in stock
		$phone_cost = htmlspecialchars($phone_cost); // Item price
		$phone_description = htmlspecialchars($phone_description); // Item description

		if ((strlen($phone_brand) >= 1) && (strlen($phone_model) >= 1) && (is_numeric($phone_memory)) && (is_numeric($phone_stock)) && (is_numeric($phone_cost)))
		{
			$product = new Product();
			$product = $product->constructor($phone_brand, $phone_model, $phone_memory, $phone_stock, $phone_cost, $phone_description);
			$product->insert_item();
		}
		else if (strlen($phone_brand) < 1)
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: The brand field is empty. Please fill correctly the form</p>
				</div>";
		}
		else if (strlen($phone_model) < 1)
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: The model field is empty. Please fill correctly the form</p>
				</div>";
		}
		else if (!is_numeric($phone_memory))
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid input for memory field because it's not a number, please complete again the form</p>
				  </div>";
		}
		else if (!is_numeric($phone_stock))
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid input for stock field because it's not a number, please complete again the form</p>
				</div>";
		}
		else if (!is_numeric($phone_cost))
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid input for cost field because it's not a number, please complete again the form</p>
				   </div>";
		}
		else
		{
			echo "<div id=\"errorbox\">
						<p>ERROR: Form is incomplete. Please fill correctly the form</p>
					</div>";
		}
    }
    else
	{
		echo "<div id=\"firsttrybox\">
					<p>Please fill the form with item brand, model, memory, units and price, description is optional<br>
					   Click after on button to add the wanted item on database.<p>
				</div>";
	}

?>
</div>
	<footer>
		<span id="time_date"></span>
    	<script type="text/javascript">window.onload = time_date('time_date');</script>&nbsp;&nbsp;&nbsp;
    			- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
    </footer>

</body>
</html>