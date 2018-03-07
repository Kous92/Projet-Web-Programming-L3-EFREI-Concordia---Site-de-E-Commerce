<?php

	require_once("database.php");

	class User
	{
		private $user_id;
		private $user_email;
		private $user_first_name;
		private $user_last_name;
		private $user_phone;
		private $password;
		private $password_encrypted;
		private $password_salt;
		private $email_validation_string;
		private $user_type;

		public function __construct() 
		{
			$this->user_id = null;
			$this->user_email = null;
		 	$this->user_first_name = null;
			$this->user_last_name = null;
			$this->user_phone = null;
			$this->password = null;
			$this->password_encrypted = null;
			$this->password_salt = null;
			$this->email_validation_string = null;
			$this->user_type = null;
		}

		public function constructor($user_email, $user_first_name, $user_last_name, $user_phone, $password, $user_type)
		{
			$instance = new self();

			$instance->user_email = $user_email;
			$instance->user_first_name = $user_first_name;
			$instance->user_last_name = $user_last_name;
			$instance->user_phone = $user_phone;
			$instance->password = $password;
			$instance->password_salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM); // Random salt
			$instance->password_encrypted = password_hash($password, PASSWORD_BCRYPT); // crypt() is deprecated on PHP 7 
			$instance->email_validation_string = "TODO_EMAIL_VALIDATION";
			$instance->user_type = $user_type;

			return $instance;
		}

		public function constructor_login($user_email, $password)
		{
			$instance = new self();

			$instance->user_email = $user_email;
			$instance->password = $password;

			return $instance;
		}

		public function insert_new_user()
		{
			// 1 - Make sure the username is available
			// 2 - Generate a random salt to encode the password
			// 3 - Encrypt password
			// 4 - Insert user in database

			// Query Database to validate user
			$SQLCommand = "SELECT * FROM UserInfo WHERE Username = '$this->user_email'";
			$result = query_database($SQLCommand);

			// If user doesn't exist in the database
			if (mysqli_num_rows($result) == 0)
			{
				// Insert user in database
				$SQLCmd = "INSERT INTO UserInfo(UserID, Username, UserFirstName, UserLastName, UserPhone, PasswordEncrypted, PasswordSalt, EmailValidationString, UserType) VALUES (NULL, '$this->user_email', '$this->user_first_name', '$this->user_last_name', '$this->user_phone', '$this->password_encrypted','$this->password_salt', 'TODO_EMAIL_VALIDATION', '$this->user_type');";
				
				$result = query_database("$SQLCmd");

				if (!$result) 
				{
					die("<div id=\"errorbox\"><p>Error: request failure with " .$SQLCommand. "</p></div>");
				}
				else
				{
					$this->send_email_confirmation($this->user_email, $this->user_first_name);
				}
			}
			else
			{
				echo "<div id=\"errorbox\">
					<p>ERROR: The email you have set is already taken. Please try with an other email.<p>
				</div>";
			}
		}

		public function validate_user()
		{
			// Query Database to validate user
			$SQLCmd = "SELECT * FROM UserInfo WHERE Username = '$this->user_email'";
			$result = query_database($SQLCmd);

			// Analyse Results
			// There should be only 1 result, username unique
			// $validated: 0 = incorrect password, 1 = OK, 2 = inexistent user name
			$validated = 0;

			if (($result) && ($result->num_rows > 0)) 
			{
				while ($row = $result->fetch_assoc())
				{
					$this->password_encrypted = $row['PasswordEncrypted'];
					$this->user_first_name = $row['UserFirstName'];
					$this->user_type = $row['UserType'];
				}

				// A random salt will be applied to encrypt password to check matching encrypted password.
				if (password_verify($this->password, $this->password_encrypted))
				{
					$validated = 1;
				}
				else
				{
					$validated = 0;
				}
			}
			else
			{
				$validated = 2;
			}

			return $validated;
		}

		public function getUserFirstName()
		{
			return $this->user_first_name;
		}

		public function getUserType()
		{
			return $this->user_type;
		}

		public function getUserEmail()
		{
			return $this->user_email;
		}

		public function send_email_confirmation($email, $first_name)
		{
			$test_body = "<b>Welcome to the community $first_name.</b>
						  <br>This is the confirmation email you have received for your registration.</br>";

			$success = send_email($email, "Online store confirmation of registration", $test_body);

			if ($success)
			{
				echo "<div id=\"successbox\">
						<p>Welcome to the community " .$first_name . ". A confirmation email<br>
						   has been sent to finish your registration.</p>
					</div>";
			}
			else
			{
				echo "<div id=\"errorbox\">
						<p>ERROR: There was a problem for sending the email to $email<p>
					</div>";
			}
		}
	}
?>