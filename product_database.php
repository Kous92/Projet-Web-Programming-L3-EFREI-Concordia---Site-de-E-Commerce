<?php
	
	require_once("database.php");

	class Product
	{
		private $product_id;
		private $product_brand;
		private $product_model;
		private $product_memory;
		private $product_stock;
		private $product_cost;
		private $product_description;

		public function __construct()
		{
			$this->product_id = null;
			$this->product_brand = null;
			$this->product_model = null;
			$this->product_memory = null;
			$this->product_stock = null;
			$this->product_cost = null;
			$this->product_description = null;
		}

		public function constructor($brand, $model, $memory, $stock, $cost, $description)
		{
			$instance = new self();

			$instance->product_brand = $brand;
			$instance->product_model = $model;
			$instance->product_memory = $memory;
			$instance->product_stock = $stock;
			$instance->product_cost = $cost;
			$instance->product_description = $description;

			return $instance;
		}

		public function insert_item()
		{
			$SQLCommand = "SELECT * FROM Products WHERE productModel = '$this->product_model'";
			$result = query_database($SQLCommand);

			$check_memory = false;

			if (($result) && ($result->num_rows > 0))
			{
				while ($row = $result->fetch_assoc())
				{
					$memory = $row['productMemory'];
				}

				if ($memory == $this->product_memory)
				{
					$check_memory = true;
				}	
			}

			// If the item doesn't exist in the database and don't have the same memory size
			if ((($result) && ($result->num_rows == 0)) || (!$check_memory))
			{
				// Insert user in database
				$SQLCmd = "INSERT INTO Products(productID, productBrand, productModel, productMemory, productStock, productCost, productDescription) VALUES (NULL, '$this->product_brand', '$this->product_model', $this->product_memory, $this->product_stock, $this->product_cost,'$this->product_description')";
				
				$result = query_database("$SQLCmd");

				if (!$result) 
				{
					die("<div id=\"errorbox\"><p>Error: request failure with " .$SQLCommand. "</p></div>");
				}
				else
				{
					echo "<div id=\"successbox\">
						<p>The item $this->product_brand $this->product_model $this->product_memory GB is successfully added on database</p>
					</div>";
				}
			}
			else
			{
				echo "<div id=\"errorbox\">
					<p>ERROR: The item $this->product_brand $this->product_model $this->product_memory GB already exists.</p>
				</div>";
			}
		}

		public function read_item($product_id)
		{
			$SQLCommand = "SELECT * FROM Products WHERE productID = $product_id";
			$results_id = query_database($SQLCommand);

			if (!$results_id) 
			{
				die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
				// return false;
			}
			else
			{
				while ($row = $results_id->fetch_assoc())
				{
					$this->product_id = $row['productID'];
					$this->product_brand = $row['productBrand'];
					$this->product_model = $row['productModel'];
					$this->product_memory = $row['productMemory'];
					$this->product_stock = $row['productStock'];
					$this->product_cost = $row['productCost'];
					$this->product_description = $row['productDescription'];
				}
			}
		}

		public function update_brand($product_id)
		{
			if (check_item_existence($product_id))
			{
				// Updating the database
				$SQLCommand = "UPDATE Products SET productBrand = '$this->product_brand' WHERE productID = $product_id";
				$result_id = query_database($SQLCommand);

				if (!$result_id) 
				{
					die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		public function update_model($product_id)
		{
			if (check_item_existence($product_id))
			{
				// Updating the database
				$SQLCommand = "UPDATE Products SET productModel = '$this->product_model' WHERE productID = $product_id";
				$result_id = query_database($SQLCommand);

				if (!$result_id) 
				{
					die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		public function update_memory($product_id)
		{
			if (check_item_existence($product_id))
			{
				// Updating the database
				$SQLCommand = "UPDATE Products SET productMemory = $this->product_memory WHERE productID = $product_id";
				$result_id = query_database($SQLCommand);

				if (!$result_id) 
				{
					die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		public function update_stock($product_id)
		{
			if (check_item_existence($product_id))
			{
				// Updating the database
				$SQLCommand = "UPDATE Products SET productStock = $this->product_stock WHERE productID = $product_id";
				$result_id = query_database($SQLCommand);

				if (!$result_id) 
				{
					die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		public function update_cost($product_id)
		{
			if (check_item_existence($product_id))
			{
				// Updating the database
				$SQLCommand = "UPDATE Products SET productCost = $this->product_cost WHERE productID = $product_id";
				$result_id = query_database($SQLCommand);

				if (!$result_id) 
				{
					die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		public function update_description($product_id)
		{
			if (check_item_existence($product_id))
			{
				// Updating the database
				$SQLCommand = "UPDATE Products SET productDescription = '$this->product_description' WHERE productID = $product_id";
				$result_id = query_database($SQLCommand);

				if (!$result_id) 
				{
					die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		public function delete_item($product_id)
		{
			$SQLCommand = "SELECT * FROM Products WHERE productID = $product_id";
			$result = query_database($SQLCommand);

			$this->read_item($product_id);

			// If the item exists in the database
			if (mysqli_num_rows($result) > 0)
			{
				// Deleting the row from database
				$SQLCommand2 = "DELETE FROM Products WHERE productID = $product_id";
				$result_id = query_database($SQLCommand2);

				if (!$result_id) 
				{
					die("<p id=\"errorbox\">Error: request failure with " .$SQLCommand. "</p>");
				}
				else
				{
					echo "<p id=\"successbox\">The item $this->product_brand $this->product_model $this->product_memory GB was successfully deleted from database</p><br>";
				}
			}
			else
			{
				echo "<p id=\"errorbox\">Error: The item does not exists in database !</p>";
			}
		}

		public function getProductID()
		{
			return $this->product_id;
		}

		public function getLastID()
		{
			$SQLCommand = "SELECT * FROM Products ORDER BY productID DESC LIMIT 1";
			$results_id = query_database($SQLCommand);

			if (!$results_id) 
			{
				die("<p id=\"error\">Error: request failure with " .$SQLCommand. "</p>");
				// return false;
			}
			else
			{
				while ($row = $results_id->fetch_assoc())
				{
					return $row['productID'];
				}
			}
		}

		public function getProductCost()
		{
			return $this->product_cost;
		}

		public function getProductStock()
		{
			return $this->product_stock;
		}

		public function setProductStock($stock)
		{
			$this->product_stock = $stock;
		}

		public function displayInCart($count)
		{
			// Print the international format for the fr_CA locale
			setlocale(LC_MONETARY, 'fr_CA');
			$currency = money_format('%i', $this->product_cost);
			
			echo "<tr>
					<td class='item_name'>$this->product_brand $this->product_model $this->product_memory GB</td>
					<td class='item_cost'>$currency</td>
					<td class='item_quantity'>$count</td>
				  <tr>";
		}

		public function itemToString($count)
		{
			// Print the international format for the fr_CA locale
			setlocale(LC_MONETARY, 'fr_CA');
			$currency = money_format('%i', $this->product_cost);
			$string = "<p>$this->product_brand $this->product_model $this->product_memory GB, unit price: $currency, quantity: $count</p>";

			return $string;
		}
	}

	function retrieve_products()
	{
		$SQLCommand = 'SELECT * FROM Products';

		return query_database($SQLCommand);
	}

	function display_all_products($brand, $model, $memory, $stock, $cost, $description)
	{
		$results_id = retrieve_products();
		$devise = "$" . "CA";

		echo "<table>
				<tr>
					<td colspan=\"7\"><h2>Smartphones actually available<h2></td>
				</tr>
				<tr>
					<th>Brand</th>
					<th>Model</th>
					<th>Memory</th>
					<th>Availability</th>
					<th>Price</th>
					<th>Description</th>
					<th>Action</th>	
				</tr>";

		while ($row = $results_id->fetch_assoc())
		{
			$productID = $row['productID'];

			if ($row['productStock'] > 0)
			{
				$available = "<p class=\"available\">In stock</p>";

				echo "<tr>
						<form action='products.php' method='post'>
						<input type='hidden' name='productID' value='$productID'>
						<td>" . $row['productBrand'] . "</td>
						<td>" . $row['productModel'] . "</td>
						<td>" . $row['productMemory'] . " GB</td>
						<td>" . $available . "</td>
						<td>" . $row['productCost'] . " $devise</td>
						<td>" . $row['productDescription'] . "</td>
						<td><input type='submit' value='Add to cart'></td>
						</form>
					</tr>";
			}
			else
			{
				$available = "<p class=\"notavailable\">Sold out, available in 2 weeks</p>";

				echo "<tr>
						<td>" . $row['productBrand'] . "</td>
						<td>" . $row['productModel'] . "</td>
						<td>" . $row['productMemory'] . " GB</td>
						<td>" . $available . "</td>
						<td>" . $row['productCost'] . " $devise</td>
						<td>" . $row['productDescription'] . "</td>
						<td></td>
					</tr>";
			}
		}

		echo "</table>";
	}

	function display_inventory($brand, $model, $memory, $stock, $cost, $description)
	{
		$results_id = retrieve_products();
		$devise = "$" . "CA";

		echo "<table>
				<tr>
					<td colspan=\"7\"><h2>Smartphones actually available<h2></td>
				</tr>
				<tr>
					<th>Brand</th>
					<th>Model</th>
					<th>Memory</th>
					<th>Units in stock</th>
					<th>Price</th>
					<th>Description</th>
				</tr>";

		while ($row = $results_id->fetch_assoc())
		{
			$productID = $row['productID'];

			echo "<tr>
					<td>" . $row['productBrand'] . "</td>
					<td>" . $row['productModel'] . "</td>
					<td>" . $row['productMemory'] . " GB</td>
					<td>" . $row['productStock'] . "</td>
					<td>" . $row['productCost'] . " $devise</td>
					<td id=\"description\">" . $row['productDescription'] . "</td>
					</form>
				</tr>";
		}

		echo "</table>";
	}

	function check_item_existence($product_id)
	{
		$SQLCommand = "SELECT * FROM Products WHERE productID = $product_id";
		$result = query_database($SQLCommand);

		if (($result) && ($result->num_rows > 0))
		{
		   	return true;
		}
		else
		{
			die("<p id=\"errorbox\">Error: the item does not exists on database !</p>");
			return false;
		}
	}

	function check_before_adding($description)
	{
		$SQLCommand = "SELECT * FROM Products WHERE productDesc='$description'";
		$result = query_database($SQLCommand);
		
		if (($result) && ($result->num_rows > 0))
		{
		   	return true;
		}
		else
		{
			return false;
		}
	}
?>