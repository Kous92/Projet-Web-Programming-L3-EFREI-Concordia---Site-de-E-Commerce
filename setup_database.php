<?php
	
	require_once("database.php");

	function reset_user_database()
	{
		$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
		$password_encrypted = password_hash("koenigsegg", PASSWORD_BCRYPT);
		$password_encrypted2 = password_hash("digitalphoneadmin", PASSWORD_BCRYPT);
		$password_encrypted3 = password_hash("BenzemaMadrid", PASSWORD_BCRYPT);
		$password_encrypted4 = password_hash("example", PASSWORD_BCRYPT);

		$results_id = query_database("DROP TABLE UserInfo");
		$results_id2 = query_database("CREATE TABLE UserInfo(UserID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY Key, Username VARCHAR(50) NOT NULL, UserFirstName VARCHAR(50) NOT NULL, UserLastName VARCHAR(50) NOT NULL, UserPhone VARCHAR(32) NOT NULL, PasswordEncrypted VARCHAR(255) NOT NULL, PasswordSalt VARBINARY(100) NOT NULL, EmailValidationString VARCHAR(32) NOT NULL, UserType VARCHAR(10) NOT NULL, UNIQUE (Username))");

		// Administrators
		$results_id3 = query_database("INSERT INTO UserInfo(UserID, Username, UserFirstName, UserLastName, UserPhone, PasswordEncrypted, PasswordSalt, EmailValidationString, UserType) VALUES (NULL, 'kous92@gmail.com', 'Koussaïla', 'BEN MAMAR', '+1 514-561-6346', '$password_encrypted','$salt', 'TODO_EMAIL_VALIDATION', 'admin');");
		$results_id4 = query_database("INSERT INTO UserInfo(UserID, Username, UserFirstName, UserLastName, UserPhone, PasswordEncrypted, PasswordSalt, EmailValidationString, UserType) VALUES (NULL, 'onlinestore.project.concordia@gmail.com', 'Admin', '', '+1 111-111-111', '$password_encrypted2','$salt', 'TODO_EMAIL_VALIDATION', 'admin');");

		$results_id5 = query_database("INSERT INTO UserInfo(UserID, Username, UserFirstName, UserLastName, UserPhone, PasswordEncrypted, PasswordSalt, EmailValidationString, UserType) VALUES (NULL, 'kb9@gmail.com', 'Karim', 'BENZEMA', '+33 6 24 70 93 34', '$password_encrypted3','$salt', 'TODO_EMAIL_VALIDATION', 'regular');");

		$results_id6 = query_database("INSERT INTO UserInfo(UserID, Username, UserFirstName, UserLastName, UserPhone, PasswordEncrypted, PasswordSalt, EmailValidationString, UserType) VALUES (NULL, 'johndoe@example.com', 'John', 'DOE', '+33 6 11 11 11 11', '$password_encrypted3','$salt', 'TODO_EMAIL_VALIDATION', 'regular');");

		if (($results_id) && ($results_id2) && ($results_id3) && ($results_id4) && ($results_id5) && ($results_id6))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function reset_product_database()
	{
		$results_id3 = query_database("DROP TABLE Products");
		$results_id4 = query_database("CREATE TABLE Products(productID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY Key, productBrand VARCHAR(50) NOT NULL, productModel VARCHAR(50) NOT NULL, productMemory INT, productStock INT UNSIGNED, productCost FLOAT, productDescription TEXT CHARACTER SET utf8)");	

		query_database("INSERT INTO Products VALUES(NULL, 'Apple', 'iPhone 7', 32, 100, 899, 'Apple iPhone 7 with 32 GB of memory')");
		query_database("INSERT INTO Products VALUES(NULL, 'Samsung', 'Galaxy S7', 32, 100, 870, 'Samsung Galaxy S7 with 32 GB of memory')");

		if (($results_id3) && ($results_id4))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

/*
	INSERT INTO UserInfo (UserID, Username, UserFirstName, UserLastName, UserPhone, PasswordEncrypted, PasswordSalt, EmailValidationString, UserType) VALUES (1, 'kous92@gmail.com', 'Koussaïla', 'BEN MAMAR', '+1 514-561-6346', '$2y$10$yv1rtd0hgHdM1vZJl5GJlu3wsO8qLyNxlKr6pQ6zWJFOJlMYLH15q', 0x5639ec94ac992a9e6a126e490e91c80891b62de8bf7b, 'TODO_EMAIL_VALIDATION', 'admin');
*/
?>