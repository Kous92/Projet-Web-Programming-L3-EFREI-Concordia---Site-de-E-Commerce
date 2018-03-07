<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Hedgehog Education</title>
	<link rel="stylesheet" type="text/css" href=".design_connexion.css">
	<script type="text/javascript" src="./JS/date_heure.js"></script>
	<title>Hedgehog Education</title>
</head>
<body>
	<header>
		<h1>Hedgehog Education</h1>
		<div class="menu">
			<ul>
				<li><a href="index.php">Accueil</a></li>
				<li><a href="inscription.php" target="_blank_">Inscris-toi</a></li>
			</ul>
		</div>
	</header>

		<div class="login">
			<p>Saisissez vos identifiants pour vous connecter à votre session<br>
			   Les champs avec <sup id="required">*</sup> sont obligatoires.</p><br>
				<table class="loginbox">
					<form action="connexion.php" method="post">	
						<tr>
							<td id="name">User email adress <sup id="required">*</sup></td>
							<td id="input"><input type="email" name="email" placeholder="Ex: johndoe@example.com" required/><br></td>
						</tr>

						<tr>
							<td id="name">Password <sup id="required">*</sup></td>
							<td id="input"><input type="password" name="password" placeholder="6 characters minimum" required/></td>
						</tr>

						<tr>
							<td><br><input type="submit" value="Log in" title="Click on this button to check your identifiers and log into your account"/></td>
							<td><br><input type="reset" value="Reset" title="Click on this button to clear the form"/></td>
						</tr>
					</form>
				</table>
		</div>

<footer>
	<span id="date_heure"></span>
    <script type="text/javascript">window.onload = date_heure('date_heure');</script> &nbsp;&nbsp;&nbsp;
    - &nbsp;&nbsp;&nbsp;Copyright Hedgehog Education, Mars 2017, tous droits réservés
</footer>

</body>
</html>