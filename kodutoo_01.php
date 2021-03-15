<?php
	$pagetitle = "Õpime PHP-d";
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
	$allfiles = array_slice(scandir($picsdir), 2); // nr 2 lõpus on scandiriga loetud kaks esimest kirjet, mis räägivad lihtsalt kataloogist, seega need ei ole pildid
	// echo $allfiles[5];
	// var_dump($allfiles);
	$allowedphototypes = ["image/jpeg", "image/png"];
	$picfiles = []; //tekitan listi

	foreach($allfiles as $file) { // for tsükkel et leida vaid pildifailid allfilest ja siis tähista iga võetud fail $file. Tsükkel läbitakse niipalju kordi, kui me $allfilesis leidsime
		$fileinfo = getimagesize($picsdir .$file); // küsime faili suurust, sest selle abil saame me veel hunniku asju teada just sellelt pildilt mh failitüübi, mida meil vaja ongi
		// var_dump($fileinfo); // edastab kogu info, et saame vaadata, mida meil kätte saada
		if(isset($fileinfo["mime"])) { // kui nüüd fileinfos on "mime" siis edasi
			if(in_array($fileinfo["mime"], $allowedphototypes)) {  // kui arrays on mime ja kas ta on allowed... massiivis
				array_push($picfiles, $file); // array_push tähendab  võtan failime ja panen file picfiles massiivi
			}
		}
	}


	$photocount = count($picfiles); // loeme üles piltide arvu
	$RandImgArray = []; // tekitan listi/massiivi, kuhu kogun alljärgnevalt 3 random pilti

	// kasutame do while funktsiooni
	// loome uue listi, kuhu lükkame meile sobivad pildid, st siis unikaalsed pildid. while counter leondab, palju seal listis pilte on
	do {
		$RandImg = mt_rand(0, $photocount-1); //leiame esimese suvapildi mt_rand funktsiooniga. Sulgudes on vahemik, millest milleni otsime.
		if(!(in_array($RandImg, $RandImgArray))) { //Kui pilti pole RandImgArray nimelises massiivis/listis, siis:
			array_push($RandImgArray, $RandImg); // lükkame pildi massiivi
		}
	} while (count($RandImgArray) < 3);  //käitame tsüklit 3 korda, ehk jooksutame, kuni RandImgArrayis on 3 pilti.

	// nüüd defineerime, mis kohal RandImg listis mingi pilt täpselt on
	$randomphoto = $picfiles[$RandImgArray[0]]; // seega randomfoto on pildifailide folderist pilt nr $RandImg
	$randomphoto2 = $picfiles[$RandImgArray[1]];
	$randomphoto3 = $picfiles[$RandImgArray[2]];

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
	<h1>
	<?php
		echo $pagetitle;
	?>
	</h1>
	<h2>
	<?php
		echo $myname;
	?>
	</h2>
	<p>See leht on valminud õppetöö raames!</p>
	<?php
		echo $timehtml;
		echo $semesterdurhtml;
		echo $semesterprogress;
	?>

	<p>Täna on

	<?php
	setlocale(LC_TIME, 'et_EE.utf8');
	$date =  strftime('%A.');
	echo $date;
	?>
	</p>

	<div class="row">
		<div class="d-md-flex">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$randomphoto; ?>" alt="suvapilt"> <!-- echo järel ütlen, mis kataloogis, mis pilt asub -->
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$randomphoto2; ?>" alt="suvapilt">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$randomphoto3; ?>" alt="suvapilt">
		</div>
</div>

	<?php
		$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		echo "<a href='$url'>Tagasi</a>"; 
	?>
	<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script type="module" src="assets/js/starter.js"></script>  
</body>
</html>