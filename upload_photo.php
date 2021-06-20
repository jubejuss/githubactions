<?php
	require_once "usesession.php";
	require_once "dbconf.php"; // sellega lisame siia dbconf.php faili, kus on kirjas andmebaasi andmed
	require_once "fnc_general.php";
	require_once "local_remote_photo_variables.php";
	require_once "fnc_upload_photo.php";
	require_once "classes/Upload_photo.class.php";

	$photo_upload_error = null;
	$image_file_type = null;
	$image_file_name = null;
	$file_size_limit = 1 * 1024 * 1024;
	$image_max_w = 600;
	$image_max_h = 400;
	$image_thumbnail_size = 100;
	$notice = null;
	$watermark = "images/vr_watermark.png";
	
	if(isset($_POST["photo_submit"])){
		
		$photo_upload = new Upload_photo($_FILES["file_input"],$file_size_limit);
		$photo_upload_error .= $photo_upload->photo_upload_error;
	
		if(empty($photo_upload->photo_upload_error)){
		
			//suuruse muutmine
			$photo_upload->resize_photo($image_max_w, $image_max_h);
	
			// lisan vesimärgi
			$photo_upload->add_watermark($watermark);
			
			//salvestame pikslikgumi faili
			$image_file_name = $photo_upload->filename();
			$target_file = "upload_photos_normal/" .$image_file_name;
			$result = $photo_upload->save_image_to_file($target_file, false);
			if($result == 1) {
				$notice = "Vähendatud pilt laeti üles! ";
			} else {
				$photo_upload_error = "Vähendatud pildi salvestamisel tekkis viga!";
			}
			
			//teen pisipildi
			$photo_upload->resize_photo($image_thumbnail_size, $image_thumbnail_size, false);
			
			//salvestame pisipildi faili
			$target_file = "upload_photos_thumbs/" .$image_file_name;
			$result = $photo_upload->save_image_to_file($target_file, false);
			if($result == 1) {
				$notice .= " Pisipilt laeti üles! ";
			} else {
				$photo_upload_error .= " Pisipildi salvestamisel tekkis viga!";
			}

			$target_file = "upload_photos_orig/" .$image_file_name;
				//if(file_exists($target_file))
				if(move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)){
					$notice .= " Originaalfoto üleslaadimine õnnestus!";
				} else {
					$photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus!";
				}
	
			$photo_upload_error = $photo_upload->photo_upload_error;
			unset($photo_upload);

			if($photo_upload_error == null){
				$result = store_photo_data($image_file_name, $_POST["alt_input"], $_POST["privacy_input"], $_FILES["file_input"]["name"]);
				if($result == 1){
					$notice .= " Pildi andmed lisati andmebaasi!";
				} else {
					$photo_upload_error = "Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}
			
		}
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
<!-- defer pidurdab javascripti kohest käivitamist -->
<script src="javascript/checkImageSize.js" defer></script>
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
										<label for="file_input">Vali foto fail! </label>
										<input id="file_input" name="file_input" type="file">
										<br>
										<label for="alt_input">Alternatiivtekst ehk pildi selgitus</label>
										<input id="alt_input" name="alt_input" type="text" placeholder="Pildil on ...">
										<br>
										<label>Privaatsustase: </label>
										<br>
										<input id="privacy_input_1" name="privacy_input" type="radio" value="3" checked>
										<label for="privacy_input_1">Privaatne</label>
										<br>
										<input id="privacy_input_2" name="privacy_input" type="radio" value="2">
										<label for="privacy_input_2">Registreeritud kasutajatele</label>
										<br>
										<input id="privacy_input_3" name="privacy_input" type="radio" value="1">
										<label for="privacy_input_3">Avalik</label>
										<br>
		<!-- name on vajalik PHP-le, ID on vajalik javascriptile -->
		<input type="submit" id="photo_submit" name="photo_submit" value="Lae pilt üles!">
									</form>
		<p id="notice"><?php echo $photo_upload_error; echo $notice; ?></p>




					
							<a href="./">Aine kodulehele</a>
							<p>Juhhei</p>
						</div>
					</div>
				</main>
				<?php require("page_details/scripts.php") ?>
			</body>
			</html>