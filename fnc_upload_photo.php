<?php

						function resize_photo($src, $w, $h, $keep_orig_proportion = true){
							$image_w = imagesx($src);
							$image_h = imagesy($src);
							$new_w = $w;
							$new_h = $h;
							$cut_x = 0;
							$cut_y = 0;
							$cut_size_w = $image_w;
							$cut_size_h = $image_h;
							
							if($w == $h){
								if($image_w > $image_h){
									$cut_size_w = $image_h;
									$cut_x = round(($image_w - $cut_size_w) / 2);
								} else {
									$cut_size_h = $image_w;
									$cut_y = round(($image_h - $cut_size_h) / 2);
								}	
							} elseif($keep_orig_proportion){//kui tuleb originaaproportsioone säilitada
								if($image_w / $w > $image_h / $h){
									$new_h = round($image_h / ($image_w / $w));
								} else {
									$new_w = round($image_w / ($image_h / $h));
								}
							} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
								if($image_w / $w < $image_h / $h){
									$cut_size_h = round($image_w / $w * $h);
									$cut_y = round(($image_h - $cut_size_h) / 2);
								} else {
									$cut_size_w = round($image_h / $h * $w);
									$cut_x = round(($image_w - $cut_size_w) / 2);
								}
							}
							
							//loome uue ajutise pildiobjekti
							$my_new_image = imagecreatetruecolor($new_w, $new_h);
							//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
							imagesavealpha($my_new_image, true);
							$trans_color = imagecolorallocatealpha($my_new_image, 0, 0, 0, 127);
							imagefill($my_new_image, 0, 0, $trans_color);
							imagecopyresampled($my_new_image, $src, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
							return $my_new_image;
						}

						function save_image_to_file($new_temp_image, $target, $image_File_type){
							$notice = null;
							if($image_File_type == "jpg"){
								if(imagejpeg($new_temp_image, $target, 90)){
									$notice = 1;
								} else {
									$notice = 0;
								}
							}
							if($image_File_type == "png"){
								if(imagepng($new_temp_image, $target, 6)){
									$notice = 1;
								} else {
									$notice = 0;
								}
							}
							return $notice;
						}

						function store_photo_data($image_file_name, $alt, $privacy, $orig_name){
							$notice = null;
							$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
							$stmt = $conn->prepare("INSERT INTO vr21_photos (vr21_photos_userid, vr21_photos_filename, vr21_photos_alttext, vr21_photos_privacy, vr21_photos_origname) VALUES (?, ?, ?, ?, ?)");
							echo $conn->error;
							$stmt->bind_param("issis", $_SESSION["user_id"], $image_file_name, $alt, $privacy, $orig_name);
							if($stmt->execute()){
								$notice = 1;
							} else {
								$notice = $stmt->error;
							}
							
							$stmt->close();
							$conn->close();
							return $notice;
						}

function store_news_photo_data($image_file_name, $alt, $orig_name){
	$notice = null;
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vr21_news_photos (vr21_news_photos_userid, vr21_news_photos_filename, vr21_news_photos_alttext, vr21_news_photos_origname) VALUES (?, ?, ?, ?)");
	echo $conn->error;
	$stmt->bind_param("isss", $_SESSION["user_id"], $image_file_name, $alt, $orig_name);
	if($stmt->execute()){
		$notice = 1;
	} else {
		$notice = $stmt->error;
	}
	$photo_id = $conn->insert_id;
	$stmt->close();
	$conn->close();
	return array($notice, $photo_id);
}

function update_news_photo_data($image_file_name, $alt, $orig_name){
	$notice = null;
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	$stmt = $conn->prepare("UPDATE vr21_news_photos (vr21_news_photos_userid, vr21_news_photos_filename, vr21_news_photos_alttext, vr21_news_photos_origname) VALUES (?, ?, ?, ?)");
	echo $conn->error;
	$stmt->bind_param("isss", $_SESSION["user_id"], $image_file_name, $alt, $orig_name);
	if($stmt->execute()){
		$notice = 1;
	} else {
		$notice = $stmt->error;
	}
	$photo_id = $conn->insert_id;
	$stmt->close();
	$conn->close();
	return array($notice, $photo_id);
}

						function gallery() {
							$privacy = 2;
							$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
							$stmt = $conn->prepare("SELECT vr21_photos.vr21_photos_id, vr21_photos.vr21_photos_filename, vr21_photos.vr21_photos_alttext, vr21_users.vr21_users_firstname, vr21_users.vr21_users_lastname FROM vr21_photos JOIN vr21_users ON vr21_photos.vr21_photos_userid = vr21_users.vr21_users_id WHERE vr21_photos.vr21_photos_privacy <= ? AND vr21_photos.vr21_photos_deleted IS NULL GROUP BY vr21_photos.vr21_photos_id");
							echo $conn -> error;
							$stmt -> bind_param("i", $privacy);
							$stmt -> bind_result($photos_id, $photos_filename, $photos_alttext, $users_firstname, $users_lastname);
							$stmt -> execute();
							$photos = null;
							while ($stmt -> fetch()) {
								$photos .= '<div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center">';
								$photos .= '<img src="../upload_photos_thumbs/' .$photos_filename .'" alt="' .$photos_alttext .'" class="thumb rounded" data-fn="' . $photos_filename .'"  data-id="'. $photos_id.'">';
								$photos .= '<p class="mt-2 font-italic">'. "Autor: " . $users_firstname ." " .$users_lastname .'</p></div>';

							}
							$stmt -> close();
							$conn -> close();
							return $photos;
						}