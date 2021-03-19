<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

	require_once "../../conf.php";

	function read_news() {
		// echo $news_title .$news_content .$news_author; // kuvan lihtsalt
		// echo $GLOBALS["server_host"];
		// loome andmebaasiga ühenduse
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		// määrame suhtluseks kodeeringu
		$conn = set_charset("utf8");
		// valmistan ette SQL käsu
		$stmt = $conn -> prepare("SELECT vr21_news_title, vr21_news_content, vr21_news_author FROM v21_news"); 
		echo $conn -> error;
		$stmt -> bind_result($news_title_from_db, $news_content_from_db, $news_author_from_db);
		$stmt -> execute();
		$raw_news_html = null;
		while ($stmt -> fetch()) {
			$raw_news_html .= "\n <h2>" .$news_title_from_db ."</h2>";
			$raw_news_html .= "\n <p>" .nl2br($news_content_from_db) ."</p>";
			$raw_news_html .= "\n <p>Edastas: ";
			if(!empty($news_author_from_db)) {
				$raw_news_html .= $news_author_from_db;
			} else {
				$raw_news_html .= "Tundmatu reporter";
			}
			$raw_news_html .= "</p>";
		};
		$stmt -> close();
		$conn -> close();
		return $raw_news_html;
	}

	$news_html = read_news();

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
	<h1>Uudiste näitamine</h1>

	<?php echo $news_html; ?>

	<p><?php echo $news_input_error; ?></p>

<div>
	<a href="./">Aine kodulehele</a>
</div>
	<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script type="module" src="assets/js/starter.js"></script>  
</body>
</html>