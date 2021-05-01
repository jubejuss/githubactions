<?php
	/*session_start();
    //kas on sisse  loginud
    if(!isset($_SESSION["user_id"])) {
        header("Location: page.php");
    }
    // välja logimine
    if(isset($_GET["logout"])) {
        session_destroy();
        header("Location: page.php");
    }*/
    require_once "usesession.php";
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/starter.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Home</title>
</head>
<body class="bg-gradient-secondary text-bright">
    <header>
        <?php include("page_details/navbar.php"); ?>
    </header>
    <main>
        <div class="container">
            <h1>Sisse loginud kasutaja</h1>
            <p>Palju õnne, oled sisseloginud!</p>
            <hr>
            <p><a href="?logout=1">Logi välja</a></p>
         </div>
    </main>
	<?php require("page_details/scripts.php") ?>
</body>
</html>