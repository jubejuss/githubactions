<?php

	$news_input_error = null;	
	// var_dump($_POST); // lihtsalt vaatan, mida sisestasin, on olemas $_get
	if(isset($_POST["news_submit"])) { // kui nuppu "news_submit" klõpsatakse, siis
		// echo "klõpsati"; // lihtne test, kas eelmine rida töötab
		if(empty($_POST["news_title_input"])) { // kui on tühi
			$news_input_error = "Uudise pealkiri on puudu! "; // siin annan errorile sisu
		}
		if(empty($_POST["news_content_input"])) {
			$news_input_error .= "Uudise tekst on puudu!"; // lisan eelmise rea errorteatele ehk .=
		}
		if(empty($news_input_error)){ 
			// salvestame andmebaasi
		}
	}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Veebirakendused ja nende loomine 2021</title>
	<link rel="stylesheet" href="assets/css/starter.css">
	<link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="container bg-gradient-secondary text-bright">
	<h1>Uudiste lisamine</h1>

	<form method="POST">
		<label for="news_title_input">Uudise pealkiri</label>
		<br>
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri">
		<br>
		<label for="news_content_input">Uudise tekst</label>
		<br>
		<textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" rows="6" cols="40"></textarea>
		<br>
		<label for="news_author_input">Uudise lisaja nimi</label>
		<br>
		<input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi">
		<br>
		<input type="submit" name="news_submit" value="Salvesta uudis">
	</form>
	<p><?php echo $news_input_error; ?></p>

<div>
	<a href="./">Aine kodulehele</a>
</div>
	<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script type="module" src="assets/js/starter.js"></script>  
</body>
</html>