<?php
	require_once "usesession.php";
	require_once "dbconf.php"; // sellega lisame siia dbconf.php faili, kus on kirjas andmebaasi andmed
	require_once "fnc_general.php";
	require_once "local_remote_photo_variables.php";
	include "fnc_photo.php";

	$photo_upload_error = null;
	$photo_upload_succeeded= null;
	$image_file_type = null;
	$image_file_name = null;
	$file_name_prefix = "vr_";
	$file_size_limit = 1 * 1024 * 1024;
	$image_max_w = 600;
	$image_max_h = 400;
	$privacy = null;
	$alt_text = null;
	$new_temp_image = null;
	$notice = null;
	if(isset($_POST["photo_submit"])){
		//var_dump($_POST);
		// var_dump($_FILES);
		$orig_name = $_FILES["file_input"]['name'];
		//kas üldse on pilt
		$check = getimagesize($_FILES["file_input"]["tmp_name"]);
		if($check !== false){
			//kontrollime, kas aktepteeritud failivorming ja fikseerime laiendi
			if($check["mime"] == "image/jpeg"){
				$image_file_type = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$image_file_type = "png";
			} else {
				$photo_upload_error = "Pole sobiv formaat! Ainult jpg ja png on lubatud!";
			}
		} else {
			$photo_upload_error = "Tegemist pole pildifailiga!";
		}
		
		if(empty($photo_upload_error)){
			//ega pole liiga suur fail
			if($_FILES["file_input"]["size"] > $file_size_limit){
				$photo_upload_error = "Valitud fail on liiga suur! Lubatud kuni 1MiB!";
			}
			
			if(empty($photo_upload_error)){
				//loome oma failinime
				$timestamp = microtime(1) * 10000;
				$image_file_name = $file_name_prefix .$timestamp ."." .$image_file_type;

				 //loome pikslikogumi ehk image objekti
				 $temp_image = null;
				 if($image_file_type == "jpg"){
					 $temp_image = imagecreatefromjpeg($_FILES["file_input"]["tmp_name"]);
				 }
				 if($image_file_type == "png"){
					 $temp_image = imagecreatefrompng($_FILES["file_input"]["tmp_name"]);
				 }

				//suuruse muutmine thumbnail
				$new_temp_image_thumb = image_resize_thumb($temp_image, 100, 100, true);

				$target_file = $target_file_path_t .$image_file_name;
				if($image_file_type == "jpg"){
					if(imagejpeg($new_temp_image_thumb, $target_file, 90)){
						$photo_upload_succeeded = "Thumb on salvestatud!";
					} else {
						$photo_upload_error = "Thumbi ei salvestatud!";
					}
				}
				if($image_file_type == "png"){
					if(imagepng($new_temp_image_thumb, $target_file, 6)){
						$photo_upload_succeeded = "Thumb on salvestatud!";
					} else {
						$photo_upload_error = "Thumbi ei salvestatud!";
					}
				}
				
				//suuruse muutmine normal
				$new_temp_image = image_resize($temp_image, $image_max_w, $image_max_h, false);

				//salvestame pikslikgumi faili
				$target_file = $target_file_path_n .$image_file_name;
				if($image_file_type == "jpg"){
					if(imagejpeg($new_temp_image, $target_file, 90)){
						$photo_upload_succeeded .= "Vähendatud pilt on salvestatud!";
					} else {
						$photo_upload_error .= "Vähendatud pilti ei salvestatud!";
					}
				}
				if($image_file_type == "png"){
					if(imagepng($new_temp_image, $target_file, 6)){
						$photo_upload_succeeded.= "Vähendatud pilt on salvestatud!";
					} else {
						$photo_upload_error .= "Vähendatud pilti ei salvestatud!";
					}
				}
				
				
				//$target_file = "../upload_photos_orig/" .$_FILES["file_input"]["name"];
				$target_file = $target_file_path_o .$image_file_name;
				//if(file_exists($target_file))
				if(move_uploaded_file($_FILES["file_input"]["tmp_name"], $target_file)){
					$photo_upload_succeeded .= " Foto üleslaadimine õnnestus!";
				} else {
					$photo_upload_error .= " Foto üleslaadimine ebaõnnestus!";
				}
				if(isset($_POST['privacy_input'])) {
					$privacy = intval($_POST['privacy_input']);
				
				}
				if(isset($_POST['alt_text'])){
					$alt_text = $_POST['alt_text'];
				}
				if(empty($photo_upload_error)){
					$user_id = $_SESSION['user_id'];
					$notice = photo_to_sql($user_id, $image_file_name, $orig_name, $alt_text, $privacy);
						if ($notice == 1) {
							$notice = "Pildi andmed on edukalt andmebaasis!";
						} else {
							$notice = "Pildi andmete andmebaasi lisamisel tekkis tõrge!";
						}
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
			<p><?php echo $photo_upload_error; echo $photo_upload_succeeded; ?></p>
		</date>
	</main>
	<?php require("page_details/scripts.php") ?>
</body>
</html>