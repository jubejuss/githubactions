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

	$notice = null;

    $news_id_from_page = (int)$_REQUEST["news_id"];
    $news_info_from_db = edit_news($news_id_from_page);
    $title = $news_info_from_db[0];
	$content = $news_info_from_db[1];
	$author_memory = $news_info_from_db[2];
    $image_html = $news_info_from_db[3];
    $photo_id_from_db = $news_info_from_db[4];
    $photo_alttext_from_db = $news_info_from_db[5];
    $new_photo = 0;
    $result = null;


    function edit_news($id){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn -> set_charset("utf8");
		$stmt = $conn -> prepare("SELECT vr21_news.vr21_news_id, vr21_news.vr21_news_title, vr21_news.vr21_news_content, vr21_news.vr21_news_author, vr21_news.vr21_news_photo, vr21_news.vr21_news_added, vr21_news_photos.vr21_news_photos_id, vr21_news_photos.vr21_news_photos_filename, vr21_news_photos.vr21_news_photos_alttext FROM vr21_news LEFT JOIN vr21_news_photos ON vr21_news.vr21_news_photo = vr21_news_photos.vr21_news_photos_id  WHERE vr21_news_photos.vr21_news_photos_deleted IS NULL GROUP BY vr21_news_photos.vr21_news_photos_id ORDER BY vr21_news.vr21_news_id DESC ");
		echo $conn -> error;
		$stmt -> bind_result($news_id_from_db, $news_title_from_db, $news_content_from_db, $news_author_from_db, $news_photo_id_from_db, $news_added_from_db, $photo_id_from_db, $photo_filename_from_db, $photo_alttext_from_db);
		$stmt -> execute();	
        $news_info_from_db = null;	
        while ($stmt -> fetch() ) {
            if ($id === $news_id_from_db) {
                $title = $news_title_from_db;
                $content = $news_content_from_db;
                $author_memory = $news_author_from_db;
                $picture = "<label>Praegune foto:.$photo_alttext_from_db</label>";
                $picture .= '<img class="edit_photo" src="../news_photos_normal/' .$photo_filename_from_db .'" alt="' .$photo_alttext_from_db .'" class="thumb" data-fn="'.$photo_filename_from_db .'" data-id="'.$photo_id_from_db.'">';
                $news_info_from_db = [$news_title_from_db, $news_content_from_db, $news_author_from_db, $picture, $photo_id_from_db, $photo_alttext_from_db];
            }
        }
		$stmt -> close();
		$conn -> close();
        return $news_info_from_db;
	}
    // uudise uuendamine
	if(isset($_POST["news_submit"])) {
        //  header viib tagasi show_news.php lehele
        header('location: show_news.php');

        $news_id_from_page = $_POST["news_id_input"];
        $photo_id_from_db = $_POST["photo_id_input"];
        if($_FILES["file_input"]["size"] > 0) {
            
            // Foto osa
            //Võtame kasutusele Upload_photo klassi
            $photo_upload = new Upload_photo($_FILES["file_input"],$file_size_limit);
            $photo_upload_error .= $photo_upload->photo_upload_error;

            if(empty($photo_upload->photo_upload_error)){
            
                //suuruse muutmine
                $photo_upload->resize_photo($image_max_w, $image_max_h);

                //salvestame pikslikgumi faili
                // ja muutuja andmebaasi faili nime jaoks.
                $image_file_name = $photo_upload->generate_filename();
                $target_file = "../news_photos_normal/" .$image_file_name;
                $result = $photo_upload->save_image_to_file($target_file, false);
                if($result == 1) {
                    $notice = "Vähendatud pilt laeti üles! ";
                } else {
                    $photo_upload_error = "Vähendatud pildi salvestamisel tekkis viga!";
                }
                
                // originaal faili puhul kasutan näitena orginaal nime
                $target_file = "../news_photos_orig/" .$_FILES["file_input"]["name"];
                $result = $photo_upload->save_image_to_file($target_file, true);
                if($result == 1){
                    $notice .= " Originaalfoto üleslaadimine õnnestus!";
                } else {
                    $photo_upload_error .= " Originaalfoto üleslaadimine ebaõnnestus!";
                }

                $photo_upload_error = $photo_upload->photo_upload_error;
                unset($photo_upload);
                //kui kõik hästi, salvestame pildi info andmebaasi!!!
                if($photo_upload_error == null && $photo_id_from_db > 0){
                    $result = null;
                    $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
                    $conn -> set_charset("utf8");
                    $stmt = $conn->prepare("UPDATE vr21_news_photos SET vr21_news_photos_filename = ?, vr21_news_photos_alttext = ? WHERE vr21_news_photos_id = ?");
                    echo $conn->error;
                    $stmt->bind_param("ssi", $image_file_name, $_POST["alt_text"], $photo_id_from_db);
                    if($stmt->execute()){
                        $result = 1;
                    } else {
                        $result = $stmt->error;
                    }
                    $photo_id = $conn->insert_id;
                    $stmt->close();
                    $conn->close();
                    if($result == 1){
                        $notice .= " Pildi andmed lisati andmebaasi!";
                    } else {
                        $photo_upload_error = "Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
                    }
                }
                // Kui uudisel polnud pilti ja me soovime seda lisada
                if($photo_upload_error == null && $photo_id_from_db <= 0) {
                    $result = store_news_photo($_SESSION["user_id"], $image_file_name, $_POST["alt_text"]);
                    if($result[0] == 1){
                        $notice .= " Pildi andmed lisati andmebaasi!";
                        $new_photo = 1;
                    } else {
                        $photo_upload_error = "Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
                    }
                }
            }
            
            if(empty($news_input_error)){
                // 3 input rida mis validaator funktsioonist läbi käivad
                $news_content_input = test_input($_POST["news_content_input"]);
                $news_title_input = test_input($_POST["news_title_input"]);
                $news_author_input = test_input($_POST["news_author_input"]);

                //Kui valideeritud salvestame andmebaasi
                if($new_photo === 0) {
                    store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"], $photo_id_from_db, $news_id_from_page);
                } else { // kui lisati postitusele pilt mida enne polnud
                    store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"], $result[1], $news_id_from_page);
                }            }
        } else {
            if(empty($news_input_error)){
                // 3 input rida mis validaator funktsioonist läbi käivad
                $news_content_input = test_input($_POST["news_content_input"]);
                $news_title_input = test_input($_POST["news_title_input"]);
                $news_author_input = test_input($_POST["news_author_input"]);
    
                //Kui valideeritud salvestame andmebaasi
                    store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"], $photo_id_from_db, $news_id_from_page);
            }
        }

	} 






    
	function store_news($news_title, $news_content, $news_author, $news_photo_id, $news_id_from_page){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn -> set_charset("utf8");
		$stmt = $conn -> prepare("UPDATE vr21_news SET vr21_news_title = ?, vr21_news_content = ?, vr21_news_author = ?, vr21_news_photo_id = ? WHERE vr21_news_id = ? ");
		echo $conn -> error;
		$stmt -> bind_param("sssii", $news_title, $news_content, $news_author, $news_photo_id, $news_id_from_page);
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
		<?php echo $news_id_from_page ?>

	<hr>
	<p><a href="home.php">Avalehele</a></p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="news_title_input">Uudise pealkiri</label>
		<br>
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri" value="<?php echo $title ?>">
		<br>
		<label for="news_content_input">Uudise tekst</label>
		<br>
		<textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" rows="6" cols="40"><?php echo $content; ?></textarea>
		<br>
		<label for="news_title_input"><?php echo $image_html; ?></label>
		<br>
		<img class="mb-3" src="upload_news_photos_normal/<?php echo $photo_name; ?>" alt=""></img>
			
		<br>
		<label for="file_input">Vaheta pilti </label>
		<input id="file_input" name="file_input" type="file">
		<br>
		<label for="alt_input">Alternatiivtekst ehk pildi selgitus</label>
		<input id="alt_input" name="alt_input" type="text" value="<?php echo $photo_alttext_from_db ?>">
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