<?php
	$pagetitle = "Õpime PHP-d";
	$myname = "Juho Kalberg";
	$currenttime = date("d.m.Y H:i:s");
	$timehtml = "\n <p>Lehe avamise hetkel oli: " .$currenttime .".</p> \n";
	$semesterbegin = new DateTime("2021-1-25");
	$semesterend = new DateTime("2021-6-30");
	$semesterduration = $semesterbegin->diff($semesterend);                 // Diff funktsioon võrdleb alguse ja lõpuaega
	$semesterdurationdays = $semesterduration->format("%r%a");              // muudab ajaformaadi päevadeks

	$semesterdurhtml = "\n <p>2021 kevadsemestri kestus on " .$semesterdurationdays ." päeva.</p> \n";
	$today = date_create();                                                 // määrab mutuja tüübi
	$today = new DateTime("now");                                           // määran tänase kuupäeva
	$fromsemesterbegin = $semesterbegin->diff($today);
	$fromsemesterbegindays = $fromsemesterbegin->format("%r%a");
	$semesterprogress = "\n"  .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter>.</p>' ."\n";
	//--------------------------------------KODUTÖÖ-ÜL-1-------------------------------------------------//
	setlocale(LC_TIME, 'et_EE.utf8');                                       // Näitame eesti keeles käesolevat päeva.
	$todayname ="<p> Täna on ". strftime('%A.');
	//--------------------------------------KODUTÖÖ-ÜL-LÕPP----------------------------------------------//
	//--------------------------------------KODUTÖÖ-ÜL-2-------------------------------------------------//
	$today_manually = new DateTime();                                       // seadistame kuupäeva näitamise
	$today_manually->setDate(2020, 5, 10);                                  // muudan kuupäeva vastavalt soovile, et näha mis juhtub, kui oleks vastav kuupäev
	$iftoday = "Kui täna oleks ".$today_manually->format('d.m.Y'.",");
	                                                                        // kontrollime, kas semester kulgeb, on läbi või pole veel alanud, 
	                                                                        // sõltuvalt sellest, mis kuupäeva ülal sisestasime.
	$fromsemesterbegin = $semesterbegin->diff($today_manually);             // diff annab ajavahemiku semestri algusest $today-ni
	$fromsemesterbegindays = $fromsemesterbegin->format("%r%a");            // muuudame päevadeks
	                                                                        // võrdleme kas ajavahemik on vahemikus 0-semestri kestvus või on pikem või hoopis negatiivne
	if($fromsemesterbegindays <= $semesterdurationdays && $fromsemesterbegindays >=0) {
	    $semesterprogress_ver2 = 'leks semester omadega sealmaal: <meter min="0" max="' .$semesterdurationdays 
	    .'" value="' .$fromsemesterbegindays .'"></meter>';                 // ajavahemik on lubatud piires, seega semester kestab ja vormindame HTML muutuja mis näitab semetri kulgu
	    }    
	    else { 
	        if ($fromsemesterbegindays <0) 
	        {$semesterprogress_ver2 = " poleks semester veel alanud."; }    // ajavahemik on negatiivne, seega pole semester veel alanud
	        else {
	        $semesterprogress_ver2 = " oleks semester lõppenud.";}          // ajavahemik oli semestrist pikem ja seega semester on lõppenud
	    }
	//--------------------------------------KODUTÖÖ-ÜL-LÕPP----------------------------------------------//
	$picsdir = "images/";                                                   // loeme pildikataloogi sisu
	$allfiles = array_slice(scandir($picsdir), 2);                          // nr 2 lõpus on scandiriga loetud kaks esimest kirjet, mis räägivad lihtsalt kataloogist, seega need ei ole pildid

	$allowedphototypes = ["image/jpeg", "image/png"];
	$picfiles = [];                                                         //tekitan listi/massiivi $picfiles

	foreach($allfiles as $file) {                                           // for tsükkel et leida vaid pildifailid allfilest ja siis tähista iga võetud fail $file. Tsükkel läbitakse niipalju kordi, kui me $allfilesis leidsime
	    $fileinfo = getimagesize($picsdir .$file);                          // küsime faili suurust, sest selle abil saame me veel hunniku asju teada just sellelt pildilt mh failitüübi, mida meil vaja ongi
	    if(isset($fileinfo["mime"])) {                                      // kui nüüd fileinfos on "mime" siis edasi
	        if(in_array($fileinfo["mime"], $allowedphototypes)) {           // kui arrays on mime ja kas ta on allowed... massiivis
	            array_push($picfiles, $file);                               // array_push tähendab  võtan failime ja panen file picfiles massiivi
	        }
	    }
	}
	//--------------------------------------KODUTÖÖ-ÜL-3-------------------------------------------------//
	$randomphotofunc = array_rand($picfiles,3);                             // kuvame kolme juhuslikku pilti
	//--------------------------------------KODUTÖÖ-ÜL-LÕPP----------------------------------------------//

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
		echo "<p>";
		echo $iftoday;
		echo $semesterprogress_ver2;
		echo "</p>";
		echo $todayname;
	?>

	<div class="row">
		<h2 class="col-12">Pildid funktsiooniga</h2>
		<div class="d-md-flex">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[0]]; ?>" alt="suvapilt">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[1]]; ?>" alt="suvapilt">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[2]]; ?>" alt="suvapilt">
		</div>
		<a class="col-12" href="https://github.com/jubejuss/githubactions/blob/main/kodutoo_01_lihtsam.php">Vaata koodi Githubist</a>
	</div>

<div>
	<a href="./">Aine kodulehele</a>
</div>
	<?php require("page_details/scripts.php") ?>  
</body>
</html>