<?php
	require_once "usesession.php";
	/* session_start();
	// kas on sisse loginud
	if(!isset($_SESSION["user_id"])) {
		header("Location: kodutoo_01.php");
	}
	// välja logimine
	if(isset($_GET["logout"])) {
		session_destroy();
		header("Location: kodutoo_01.php");
	} */
	
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>Sisseloginud kasutaja</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<p><a href="?Logout=1">Logi välja</a></p>
</body>
</html>