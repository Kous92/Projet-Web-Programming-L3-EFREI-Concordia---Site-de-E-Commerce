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
	<link rel="stylesheet" type="text/css" href="./CSS/deleter.css">
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
		<h1>Digital Phone: Item deleter</h1>
		<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin_part.php" target="_blank_">Administration</a></li>
				<li><a href="admin_item_updater.php" target="_blank_">Item updater</a></li>
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

	<div class="item_deleter">
		<p>Fill the form by providing the item id to delete on database.<br>
	   	   Fields with <sup id="required">*</sup> are required to fill</p><br>

		<table class="itemdeleter">
			<form action="admin_item_deleter.php" method="post">	
				<tr>
					<td id="name"><label for="item_id">Item id<sup id="required">*</sup>: </label></td>
					<td id="input"><input type="text" name="item_id" placeholder="Ex: 1" id="item_id" required pattern="[0-9]*"><br></td>
				</tr>

				<tr>
					<td><input type="submit" value="Delete item" title="Click on this button to delete an item in database"/></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
				</tr>
			</form>
		</table>
	</div>
		
<?php
    // Security check before adding an item into database
	if (isset($_POST['item_id']))
	{
		$item_id = $_POST['item_id'];

		// Security to avoid XSS (Cross Site-Scripting) attacks
		$item_id = htmlspecialchars($item_id);

		if (is_numeric($item_id))
		{
			$product = new Product();
			$product->delete_item($item_id);
		}
		else if (!is_numeric($item_id))
		{
			echo "<div id=\"errorbox\">
					<p>ERROR: Invalid input for item id field because it's not a number, please complete again the form</p>
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
					<p>Please fill the form with item id and click after on button to delete item.<p>
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