<?php
	require_once "usesession.php";
	require_once "dbconf.php"; // sellega lisame siia dbconf.php faili, kus on kirjas andmebaasi andmed
	require_once "fnc_general.php";
	require_once "local_remote_photo_variables.php";
	
	$photo_upload_error = null;
	$image_file_type = null;
	$image_file_name = null;
	$file_name_prefix = "vr_"; //kõikide meie fotode prefixiksk pannakse
	$file_size_limit = 1 * 1024 * 1024; //1 * 1024 * 1024 vms ehk tehtena, et saaks aru ka
	$image_max_w = 600;
	$image_max_h = 400;
	if(isset($_POST["photo_submit"])){
		//var_dump($_POST); // testime, mis on üleval
		//var_dump($_FILES); // testime, kas pilt on üleval
		//kas on pilt
		$check = getimagesize($_FILES["file_input"]["tmp_name"]);
		if($check !== false) {
			//kontrollime, kas aktsepteeritud failivorming ja fikseerime laiendi
			if($check["mime"] == "image/jpeg") {
				$image_file_type = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$image_file_type = "png";
			} else {
				$photo_upload_error = "Pole sobiv formaat, ainult jpeg ja png on lubatud!";
			}
		} else {
			$photo_upload_error = "See pole pilt!!";
		}

		if(empty($photo_upload_error)){
			//ega pole liiga suur fail
			if($_FILES["file_input"]["size"] > $file_size_limit){
				$photo_upload_error = "Valitud fail on liiga suur, lubatud kuni 1MiB";
			}
			
			if(empty($photo_upload_error)){
				//Loome oma failinime
				$timestamp = microtime(1) * 10000; //annab hunniku komakohti, eepärast korrutame 10000-ga
				$image_file_name = $file_name_prefix .$timestamp ."." .$image_file_type;

				//suuruse muutmine
				//loome pikslikogumi ehk image objekti
				$temp_image = null;
				if($image_file_type == "jpg") {
					$temp_image = imagecreatefromjpeg($_FILES["file_input"]["tmp_name"]);
				}
				if($image_file_type == "png"){
					$temp_image = imagecreatefrompng($_FILES["file_input"]["tmp_name"]);
				}
				$image_w = imagesx($temp_image);
				$image_h = imagesy($temp_image);

				// kuvasuhte säilitamiseks
				if($image_w / $image_max_w > $image_h / $image_max_h) {
					$image_size_ratio = $image_w / $image_max_w;
				} else {
					$image_size_ratio = $image_h / $image_max_h;
				}

				$image_new_w = round($image_w / $image_size_ratio);
				$image_new_h = round($image_h / $image_size_ratio);

				//vähendamiseks loome uue image objekti, kuhu kopeerime vähendatud kujutise
				$new_temp_image = imagecreatetruecolor($image_new_w, $image_new_h);
				imagecopyresampled($new_temp_image, $temp_image, 0, 0, 0, 0, $image_new_w, $image_new_h, $image_w, $image_h);
				//salvestame pikslikogumi faili
				//järgmises reas on muutuja, mis on defineeritud eraldi failis local_remote_photo_variables.php
				$target_file = $target_file_path_n .$image_file_name; 
				if($image_file_type == "jpg") {
					if(imagejpeg($new_temp_image, $target_file, 90)){
						$photo_upload_error = "Vähendatud pilt on salvestatud!";
					} else {
						$photo_upload_error = "Vähendatud pilti ei salvestatud";
					}
				}
				if($image_file_type == "png") {
					if(imagepng($new_temp_image, $target_file, 6)){
						$photo_upload_error = "Vähendatud pilt on salvestatud!";
					} else {
						$photo_upload_error = "Vähendatud pilti ei salvestatud";
					}
				}
				


				//$target_file = "../upload_photos_orig/" .$_FILES["file_input"]["name"]; //näitan ära, kus me juurikast väljaspool pilte hakkame hoidma ja mis on faili nimi
				//järgmises reas on muutuja, mis on defineeritud eraldi failis local_remote_photo_variables.php
				$target_file = $target_file_path_o .$image_file_name;
				//if(file_exists($target_file))
				if(move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)){
					$photo_upload_error .= "Foto üleslaadimine õnnestus!";
				} else {
					$photo_upload_error .= "Foto üleslaadimine ebaõnnestus!";
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
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
<body class="bg-gradient-secondary text-bright">
    <header>
        <?php include("page_details/navbar.php"); ?>
    </header>
    <main>
		<div class="container">
			<h1>Fotode üleslaadimine</h1>
			<p>See leht on valminud õppetöö raames!</p>
			<hr>
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
				<label for="file_input">Vali foto fail </label>
				<input id="file_input" name="file_input" type="file"><br>
				<label for="alt_input">Alternatiivne tekst</label>
				<input id="alt_text" name="alt_text" type="text" placeholder="Pildil on..."><br>
				<label>Privaatsustase:</label>
				<label for="privacy_input_1">Privaatne</label><br>
				<input id="privacy_input_1" name="privacy_input" type="radio" value="3" checked><br>
				<label for="privacy_input_2">Registreeritud kasutajale</label><br>
				<input id="privacy_input_2" name="privacy_input" type="radio" value="2"><br>
				<label for="privacy_input_3">Avalik</label><br>
				<input id="privacy_input_3" name="privacy_input" type="radio" value="1"><br>
				<br>
				<input type="submit" name="photo_submit" value="Lae pilt üles">
			</form>
			<p><?php echo $photo_upload_error; ?></p>
		</date>
	</main>
	<?php require("page_details/scripts.php") ?>
</body>
</html>