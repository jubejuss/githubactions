<?php
	$myname = "Juho Kalberg";
	$currenttime = date("d.m.Y H:i:s");
	$timehtml = "\n <p>Lehe avamise hetkel oli: " .$currenttime .".</p> \n";
	$semesterbegin = new DateTime("2021-1-25");
	$semesterend = new DateTime("2021-6-30");
	$semesterduration = $semesterbegin->diff($semesterend);
	$semesterdurationdays = $semesterduration->format("%r%a");
	$semesterdurhtml = "\n <p>2021 kevadsemestri kestus on " .$semesterdurationdays ." päeva.</p> \n";
	$today = new DateTime("now");
	$fromsemesterbegin = $semesterbegin->diff($today);
	$fromsemesterbegindays = $fromsemesterbegin->format("%r%a");
	$semesterprogress = "\n"  .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter>.</p>' ."\n";
	//<meter min="0" max="156" value="35"></meter>
	//https://tigu.hk.tlu.ee/~andrus.rinde/vr2021/pics/
	// loeme piltide kataloogi sisu
	$picsdir = "images/";
	$allfiles = array_slice(scandir($picsdir), 2);
	// echo $allfiles[5];
	// var_dump($allfiles);
	$allowedphototypes = ["image/jpeg", "image/png"];
	$picfiles = [];

	foreach($allfiles as $file) {
		$fileinfo = getimagesize($picsdir .$file);
		// var_dump($fileinfo); edastab kogu info
		if(isset($fileinfo["mime"])) {
			if(in_array($fileinfo["mime"], $allowedphototypes)) {
				array_push($picfiles, $file);
			}
		}
	}


	$photocount = count($picfiles);

	$photonum = mt_rand(0, $photocount-1);
	
	$randomphoto = $picfiles[$photonum];
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Veebirakendused ja nende loomine 2021</title>
	<link rel="stylesheet" href="assets/css/starter.css">
</head>
<body class="container ">
	<h1>
	<?php
		echo $myname;
	?>
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p>Kasutan siin Appleboy actionit, mis võimaldab oma arvutis oleva repo Githubi pushida ja sealt selle siis automaatselt Tigu serverisse lükata</p>
	<?php
		echo $timehtml;
		echo $semesterdurhtml;
		echo $semesterprogress;
	?>
	<img class="img-fluid" src="<?php echo $picsdir .$randomphoto; ?>" alt="raudteejaam">
	<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script type="module" src="assets/js/starter.js"></script>  
</body>
</html>