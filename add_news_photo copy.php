<?php
	require_once "usesession.php";
	require_once "dbconf.php"; // sellega lisame siia dbconf.php faili, kus on kirjas andmebaasi andmed
	require_once "fnc_general.php";
	require_once "local_remote_photo_variables.php";
	require_once "fnc_upload_photo.php";
	require_once "classes/Upload_photo.class.php";

	$news_input_error = null;
	$photo_upload_error = null;
	$image_file_type = null;
	$image_file_name = null;
	$file_size_limit = 1 * 1024 * 1024;
	$image_max_w = 600;
	$image_max_h = 400;
	$image_thumbnail_size = 100;
	$notice = null;
	$photo_id = null;
	
	if(isset($_POST["news_submit"])){

		if(empty($_POST["news_title_input"])) {
			$news_input_error = "Uudise pealkiri on puudu! ";
		}
		
		if(empty($_POST["news_content_input"])) {
			$news_input_error .= "Uudise tekst on puudu!"; // lisan eelmise rea errorteatele ehk .=
		}
		if(empty($news_input_error)) { 
			if (!empty($_FILES["file_input"])) {
					$photo_upload = new Upload_photo($_FILES["file_input"],$file_size_limit);
					$photo_upload_error .= $photo_upload->photo_upload_error;
				
					if(empty($photo_upload->photo_upload_error)){
				
						//suuruse muutmine
						$photo_upload->resize_photo($image_max_w, $image_max_h);
				

					
						//salvestame pikslikgumi faili
						$image_file_name = $photo_upload->filename();
						$target_file = "upload_news_photos_normal/" .$image_file_name;
						$result = $photo_upload->save_image_to_file($target_file, false);
						if($result == 1) {
							$notice = "Vähendatud pilt laeti üles! ";
						} else {
							$photo_upload_error = "Vähendatud pildi salvestamisel tekkis viga!";
						}
					
						//teen pisipildi
						$photo_upload->resize_photo($image_thumbnail_size, $image_thumbnail_size, false);
					
						//salvestame pisipildi faili
						$target_file = "upload_news_photos_thumbs/" .$image_file_name;
						$result = $photo_upload->save_image_to_file($target_file, false);
						if($result == 1) {
							$notice .= " Pisipilt laeti üles! ";
						} else {
							$photo_upload_error .= " Pisipildi salvestamisel tekkis viga!";
						}

						$target_file = "upload_news_photos_orig/" .$image_file_name;
							//if(file_exists($target_file))
							if(move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)){
								$notice .= " Originaalfoto üleslaadimine õnnestus!";
							} else {
								$photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus!";
							}
			
						$photo_upload_error = $photo_upload->photo_upload_error;
						unset($photo_upload);

						if($photo_upload_error == null){
							list($result, $photo_id) = store_news_photo_data($image_file_name, $_POST["alt_input"], $_FILES["file_input"]["name"]);
							if($result == 1){
								$notice .= " Pildi andmed lisati andmebaasi!";
							} else {
								$photo_upload_error = "Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
							}
						}
					
					}
				}	

			// salvestame andmebaasi
			store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"], $photo_id);
		}

	}
	
	

	function store_news($news_title, $news_content, $news_author, $photo_id) {
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $conn -> prepare("INSERT INTO vr21_news (vr21_news_title, vr21_news_content, vr21_news_author, vr21_news_photo) VALUES (?,?,?,?)"); 
		echo $conn -> error;
		// seome küsimärgid päris andmetega. i - integer, s - string, d - decimal
		$stmt -> bind_param("sssi", $news_title, $news_content, $news_author, $photo_id);
		$stmt -> execute();
		$stmt -> close();
		$conn -> close();
	}




	
	?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/starter.css">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
	<title>PHP õppeleht</title>
</head>
<body class="bg-gradient-secondary text-bright">
	<header>
		<?php include("page_details/navbar.php"); ?>
	</header>
	<main>
		<div class="container bg-gradient-secondary text-bright">
			
			
		<h1>Fotode üleslaadimine</h1>
	<hr>
	<p><a href="home.php">Avalehele</a></p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="news_title_input">Uudise pealkiri</label>
		<br>
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri" value="<?php echo isset($_POST["news_title_input"]) ? $_POST["news_title_input"] : "" ?>">
		<br>
		<label for="news_content_input">Uudise tekst</label>
		<br>
		<textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" rows="6" cols="40"><?php echo isset($_POST["news_content_input"]) ? htmlspecialchars($_POST["news_content_input"]) : ""; ?></textarea>
		<br>
		<label for="news_author_input">Uudise lisaja nimi</label>
		<br>
		<input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi" value="<?php echo isset($_POST["news_author_input"]) ? $_POST["news_author_input"] : "" ?>">
		<br>
		<label for="file_input">Vali foto fail! </label>
		<input id="file_input" name="file_input" type="file">
		<br>
		<label for="alt_input">Alternatiivtekst ehk pildi selgitus</label>
		<input id="alt_input" name="alt_input" type="text" placeholder="Pildil on ...">
		<br>
		
<!-- name on vajalik PHP-le, ID on vajalik javascriptile -->
<input type="submit" id="news_submit" name="news_submit" value="Lae uudis üles!">
	</form>
<p id="notice"><?php echo $photo_upload_error; echo $notice; ?></p>
<p><?php echo $news_input_error; ?></p>





<a href="./">Aine kodulehele</a>
<p>Juhhei</p>
</div>
</div>
</main>
<?php require("page_details/scripts.php") ?>
</body>
</html>