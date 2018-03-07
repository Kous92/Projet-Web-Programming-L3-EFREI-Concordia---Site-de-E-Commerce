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
	<link rel="stylesheet" type="text/css" href="./CSS/updater.css">
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
		<h1>Digital Phone: Item updater</h1>
		<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin_part.php" target="_blank_">Administration</a></li>
				<li><a href="admin_item_deleter.php" target="_blank_">Item deleter</a></li>
				<li><a href="admin_item_adder.php" target="_blank_">Add item</a></li>
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
		<p>Fill the form to update item informations in database. Don't forget to check the wanted boxes.<br>
	   	   Fields with <sup id="required">*</sup> are required to fill.</p><br>

		<table class="itembox">
			<form action="admin_item_updater.php" method="post">	
				<tr>
					<td id="name"><label for="item_id">Item id<sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="item_id" placeholder="Ex: 1" id="item_id" required pattern="[0-9]*"><br></td>
				</tr>

				<tr>
					<td id="name"><input type="checkbox" name="update1" value="ok" id="brand"/>
					<label for="brand">Smartphone brand: </label></td>
					<td id="input"><input type="text" name="brand" placeholder="Ex: Apple" id="brand" required><br></td>
				</tr>

				<tr>
					<td id="name"><input type="checkbox" name="update2" value="ok" id="model"/>
					<label for="model">Smartphone model: </label></td>
					<td id="input"><input type="text" name="model" placeholder="Ex: iPhone" id="model" required><br></td>
				</tr>

				<tr>
					<td id="name"><input type="checkbox" name="update3" value="ok" id="memory"/>
					<label for="memory">Memory size (GB): </label></td>
					<td id="input"><input type="text" name="memory" placeholder="Ex: 16" id="memory" required pattern="[0-9]*"><br></td>
				</tr>

				<tr>
					<td id="name"><input type="checkbox" name="update4" value="ok" id="stock"/>
					<label for="stock">Units (in stock): </label></td>
					<td id="input"><input type="text" name="stock" placeholder="Ex: 100" id="stock" required pattern="[0-9]*"><br></td>
				</tr>

				<tr>
					<td id="name"><input type="checkbox" name="update5" value="ok" id="cost"/>
					<label for="cost">Smartphone price ($): </label></td>
					<td id="input"><input type="text" name="cost" placeholder="Ex: 399.99" id="cost" required pattern="[0-9]+([\.,][0-9]+)?"><br></td>
				</tr>

				<tr>
					<td id="name"><input type="checkbox" name="update6" value="ok" id="description"/>
					<label for="description">Description: </label></td>
					<td id="input"><textarea name="description" id="description" rows="2" cols="26"></textarea></td>
				</tr>

				<tr>
					<td><input type="submit" value="Update item" title="Click on this button to update an item in database"/></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
				</tr>
			</form>
		</table>
	</div>
		
<?php 
    // Security check before adding an item into database
	if ((isset($_POST['item_id'])) && (isset($_POST['brand'])) && (isset($_POST['model'])) && (isset($_POST['memory'])) && (isset($_POST['stock'])) && (isset($_POST['cost'])) && (isset($_POST['description'])))
	{
		$phone_id = $_POST['item_id'];
		$phone_brand = $_POST['brand']; // Item brand
		$phone_model = $_POST['model']; // Item model
		$phone_memory = $_POST['memory']; // In grams
		$phone_stock = $_POST['stock']; // Items in stock
		$phone_cost = $_POST['cost']; // Item price
		$phone_description = $_POST['description']; // Item description

		// Security to avoid XSS (Cross Site-Scripting) attacks
		$phone_id = htmlspecialchars($phone_id); // Item id
		$phone_brand = htmlspecialchars($phone_brand); // Item brand
		$phone_model = htmlspecialchars($phone_model); // Item model
		$phone_memory = htmlspecialchars($phone_memory); // In grams
		$phone_stock = htmlspecialchars($phone_stock); // Items in stock
		$phone_cost = htmlspecialchars($phone_cost); // Item price
		$phone_description = htmlspecialchars($phone_description); // Item description

		$product = new Product();
		$product = $product->constructor($phone_brand, $phone_model, $phone_memory, $phone_stock, $phone_cost, $phone_description);

		$success_message = "The ";
		$error_message = "ERROR: Invalid input from ";

		$error = array('update1' => false, 'update2' => false, 'update3' => false, 'update4' => false, 'update5' => false, 'update6' => false);
		$boxes = array('update1' => false, 'update2' => false, 'update3' => false, 'update4' => false, 'update5' => false, 'update6' => false);
		$error_count = 0;
		$checked_boxes = 0;
		$completed_updates = 0;

		// Checking invalid inputs on filled form
		if (isset($_POST['update1']) && $_POST['update1'] == 'ok')
		{ 
			$checked_boxes++;
			$boxes['update1'] = true;

			if (strlen($phone_brand) < 1)
			{
				$error['update1'] = true;
			}
		} 

		if (isset($_POST['update2']) && $_POST['update2'] == 'ok')
		{ 
			$checked_boxes++;
			$boxes['update2'] = true;

			if (strlen($phone_model) < 1)
			{
				$error['update2'] = true;
			}
		} 

		if (isset($_POST['update3']) && $_POST['update3'] == 'ok')
		{ 
			$checked_boxes++;
			$boxes['update3'] = true;

			if (!is_numeric($phone_memory))
			{
				$error['update3'] = true;
			}
		} 

		if (isset($_POST['update4']) && $_POST['update4'] == 'ok')
		{ 
			$checked_boxes++;
			$boxes['update4'] = true;

			if (!is_numeric($phone_stock))
			{
				$error['update4'] = true;
			}
		} 

		if (isset($_POST['update5']) && $_POST['update5'] == 'ok')
		{ 
			$checked_boxes++;
			$boxes['update5'] = true;

			if (!is_numeric($phone_cost))
			{
				$error['update5'] = true;
			}
		} 

		if (isset($_POST['update6']) && $_POST['update6'] == 'ok')
		{ 
			$checked_boxes++;
			$boxes['update6'] = true;
		} 

		foreach ($error as $error)
		{
			if ($error == true)
			{
				$error_count++;
			}
		}

		if ($checked_boxes > 0)
		{	
			if ($error_count < 1)
			{
				if (!is_numeric($phone_id))
				{
					echo "<div id=\"errorbox\">
							<p>ERROR: You don't have provided the ID of the item !</p>
						</div>";
				}
				else
				{
					if ($boxes['update1'] == true)
					{
						if ($product->update_brand($phone_id))
						{
							if ($checked_boxes > 1)
							{
								$success_message .= "brand, ";
							}
							else
							{
								$success_message .= "brand ";
							}
							
							$completed_updates++;
						}
					}

					if ($boxes['update2'] == true)
					{
						if ($product->update_model($phone_id))
						{
							if ($checked_boxes > 1)
							{
								$success_message .= "model, ";
							}
							else
							{
								$success_message .= "model ";
							}

							$completed_updates++;
						}
					}

					if ($boxes['update3'] == true)
					{
						if ($product->update_memory($phone_id))
						{
							if ($checked_boxes > 1)
							{
								$success_message .= "memory, ";
							}
							else
							{
								$success_message .= "memory ";
							}

							$completed_updates++; 
						}
					}

					if ($boxes['update4'] == true)
					{
						if ($product->update_stock($phone_id))
						{
							if ($checked_boxes > 1)
							{
								$success_message .= "stock, ";
							}
							else
							{
								$success_message .= "stock ";
							}

							$completed_updates++;
						}
					}

					if ($boxes['update5'] == true)
					{
						if ($product->update_cost($phone_id))
						{
							if ($checked_boxes > 1)
							{
								$success_message .= "boxes, ";
							}
							else
							{
								$success_message .= "boxes ";
							}

							$completed_updates++; 
						}
					}

					if ($boxes['update6'] == true)
					{
						if ($product->update_description($phone_id))
						{
							$success_message .= "description ";
							$completed_updates++; 
						}
					}

					if ($checked_boxes > 1)
					{
						$success_message .= "fields are successfully updated in database !";
					}
					else
					{
						$success_message .= "field is successfully updated in database !";
					}

					if ($completed_updates == $checked_boxes)
					{
						echo "<div id=\"successbox\">
								<p>$success_message</p>
							</div>";
					}
					else
					{
						echo "<div id=\"errorbox\">
							<p>ERROR: There is a problem on database update requests !</p>
						</div>";
					}
				}
			}
			else
			{
				if ($error['update1'] == true)
				{
					$error_message .= "brand, ";  
				}

				if ($error['update2'] == true)
				{
					$error_message .= "model, "; 
				}

				if ($error['update3'] == true)
				{
					$error_message .= "memory, ";
				}

				if ($error['update4'] == true)
				{
					$error_message .= "stock, "; 
				}

				if ($error['update5'] == true)
				{
					$error_message .= "cost, ";
				}

				if ($error['update6'] == true)
				{
					$error_message .= "description, ";
				}

				if ($checked_boxes > 1)
				{
					$error_message .= "fields.";
				}
				else
				{
					$error_message .= "field.";
				}

				echo "<div id=\"errorbox\">
							<p>$error_message</p>
						</div>";
			}
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
					<p>Please fill the form with item id, brand, model, memory, units and price, description is optional<br>
					   Check the wanted informations boxes and click after on button to update item on database.<p>
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