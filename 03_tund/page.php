<?php
	//session_start();
	SessionManager::sessionStart("vr", 0, "/~juho.kalberg/", "tigu.hk.tlu.ee");

	include('../dbconf.php'); // sellega lisame siia dbconf.php faili, kus on kirjas andmebaasi andmed
    require_once "../fnc_general.php"; // see on mul olemas, see on eelmise tunni teema, vt järele
    require_once "fnc_user.php";
	//sisselogimine
	$notice = 0;
	$email = 0;
	$email_error = 0;
	$password_error = 0;
	if(isset($_POST["login_submit"])) {
		// kontrollime, kas email ja password on olemas ja siin peaks tegema ka seda, et kas on korrektselt sisestatud.

		$notice = sign_in($_POST["email_input"], $_POST["password_input"]);
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sisselogimise leht</title>
</head>
<body>
	<h1>See on sisselogimise leht</h1>
	<h2>Logi sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>E-mail (kasutajatunnus):</label><br>
		<input type="email" name="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
		<label>Salasõna:</label><br>
		<input name="password_input" type="password"><span><?php echo $password_error; ?></span><br>
		<input name="login_submit" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span> <!-- notice muutuja annab teada, kas midagi jäi puudu vms -->
	</form>
	<p>Loo endale <a href="add_user.php">kasutajakonto</a></p>
</body>
</html>