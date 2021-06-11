<?php
	require_once "usesession.php";
	require_once "fnc_upload_photo.php";
	require_once "dbconf.php";
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Veebirakendused ja nende loomine 2021 | Galerii</title>
	<link rel="stylesheet" href="assets/css/starter.css">
	<link rel="stylesheet" href="assets/css/styles.css">
	<script src="javascript/modal.js" defer></script>
</head>
<body class="bg-gradient-secondary text-bright">
<!-- modali lisamine -->
  <!--Modaalaken fotogalerii jaoks-->
  <div id="modalarea" class="modalarea">
	<!--sulgemisnupp-->
	<span id="modalclose" class="modalclose">&times;</span>
	<!--pildikoht-->
	<div class="modalhorizontal">
		<div class="modalvertical">
			<p id="modalcaption"></p>
			<img id="modalimg" src="images/empty.png" alt="galeriipilt">

			
		</div>
	</div>
  </div>
  <!-- modali lõpp -->





    <header>
        <?php include("page_details/navbar.php"); ?>
    </header>
    <main>
        <div class="container bg-gradient-secondary text-bright">
		<h1>Galerii</h1>
			<div id="gallery" class="row">
					<?php echo gallery(); ?>
			</div>
		</div>
    </main>
    <?php require("page_details/scripts.php") ?>
</body>
</html>