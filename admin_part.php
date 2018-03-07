<?php

	if (!isset($_SESSION))
	{
	    session_start();
	}

	require_once("setup_database.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Digital Phone: Administration part</title>
	<link rel="stylesheet" type="text/css" href="./CSS/admin.css">
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
		<h1>Digital Phone: Administration part</h1>
		<div class="menu">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin_item_adder.php" target="_blank_">Add item</a></li>
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

	<div class="item_deleter">
		<p>Fill the form by choosing your answer.<br>
	   	   Fields with <sup id="required">*</sup> are required to fill</p><br>

		<table class="itemdeleter">
			<form action="admin_part.php" method="post">	
				<tr>
					<td id="name"><label for="decision">Are you sure to reset databases ? <sup id="required">*</sup>: </label></td>
					<td id="input">&nbsp;&nbsp;<input type="radio" name="decision" value="yes" id="yes"/><label for="yes">Yes</label>
								   &nbsp;&nbsp;<input type="radio" name="decision" value="no" id="no" checked="checked"/><label for="no">No</label>
					<br></td>
				</tr>

				<tr>
					<td><input type="submit" value="Reset databases" title="Click on this button to reset databases"/></td>
				</tr>
			</form>
		</table>
	</div>
		
<?php
    // Checking decision
	if (isset($_POST['decision']) && $_POST['decision'] == 'yes')
	{ 
		if (reset_user_database() && reset_product_database())
		{
			echo "<div id=\"successbox\">
						<p>The databases UserInfo and Products are now reset and empty.</p>
					</div>";
		}
		else
		{
			echo "<div id=\"errorbox1\">
						<p>ERROR: Resetting databases failure !</p>
					</div>";
		}
	} 
    else
	{
		echo "<div id=\"warningbox\">
					<p>WARNING: Take your decision carefully. If you choose yes and click on button, all<br>
						databases will be reset, all informations will be deleted !<p>
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