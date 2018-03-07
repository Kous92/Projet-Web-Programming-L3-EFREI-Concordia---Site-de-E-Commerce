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
	<link rel="stylesheet" type="text/css" href="./CSS/inventory.css">
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
			  <footer class="v1">
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
			  <footer class="v1">
			  	<span id="time_date"></span>
		    	<script type="text/javascript">window.onload = time_date(\'time_date\');</script>&nbsp;&nbsp;&nbsp;
    			- &nbsp;&nbsp;&nbsp;Copyright Koussaïla BEN MAMAR, November 2016, all rights reserved
		      </footer>';
			exit(); // Stop the execution of the rest of the code;
		}
	}
?>
<header>
		<h1>Digital Phone: Inventory</h1>
		<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin_part.php" target="_blank_">Administration</a></li>
				<li><a href="admin_item_adder.php" target="_blank_">Add item</a></li>	
				<li><a href="admin_item_deleter.php" target="_blank_">Item deleter</a></li>
				<li><a href="admin_item_updater.php" target="_blank_">Item updater</a></li>	
	<?php
	   	  echo '<li><a href="logout.php">Log out</a></li>
				<li class="welcome">Welcome ' . $_SESSION['user'] . '</li>
			</ul>
		</div>';
	?>
</header>

<?php
	$results_id = retrieve_products();

	while ($row = $results_id->fetch_assoc())
	{
		$brand = $row['productBrand'];
		$model = $row['productModel'];
		$memory = $row['productMemory'];
		$stock = $row['productStock']; 
		$cost = $row['productCost'];
		$description = $row['productDescription'];
	}

	display_inventory($brand, $model, $memory, $stock, $cost, $description);

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