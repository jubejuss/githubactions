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

	setlocale(LC_TIME, 'et_EE.utf8');                                       // Sellega määran järgmise rea keele
	$todayname ="<p> Täna on ". strftime('%A. Lihtsal, kuid võimalik, et mõningatel juhtudel mittetoimival moel – kasutatakse `setlocale` funktsiooni.'); 
	                                                                        // Selle defineerin, et $todayname on päeva nimi. A kirjutab päeva välja


	                                                                        // päeva nimetuse leidmine keeruliselt
	$weekday_nr=date('w');                                                  // date(w) on PHP funktsioon on nädalapäevade numbriline definitsioon

	                                                                        // moodustame listi/massiivi nädalapäevadega
    $day_names=['pühapäev','esmaspäev','teisipäev','kolmapäev','neljapäev','reede','laupäev'];
	                                                                        // nüüd ütleme, et võtku listist tänane päev ja kuvagu seda.
    $todaysweekdayhtml="<p> Täna on ". $day_names[$weekday_nr].". Keerulisel, kuid lollikindlal moel – andmed loetakse massiivist.</p>"; 
	                                                                        // aga kui semester pole veel alanud või on läbi, kuidas siis see näidatakse?
	                                                                        // vaata siit https://www.php.net/manual/en/datetime.setdate.php
	                                                                        // objektorienteeritud style:
	$today_ver2 = new DateTime();        
    $today_ver2->setDate(2020, 5, 10);                                      // siin muudan kuupäeva vastavalt soovile, et näha mis juhtub, kui oleks vastav kuupäev

	                                                                        // protserural style:
	                                                                        //$today_ver2 = date_create(); // vaata siit https://www.php.net/manual/en/datetime.setdate.php
	                                                                        // date_date_set($today_ver2,2023, 4, 10); // siin muudan kuupäeva vastavalt soovile, et näha mis juhtub, kui oleks vastav kuupäev

	$iftoday = "Kui täna oleks ".$today_ver2->format('d.m.Y'.",");
	                                                                        // kontrollime, kas semester kulgeb, on läbi või pole veel alanud, sõltuvalt sellest, mis kuupäeva ülal sisestasime.
    $fromsemesterbegin = $semesterbegin->diff($today_ver2);                 // diff annab ajavahemiku semestri algusest $today-ni
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


	                                                                        // loeme piltide kataloogi sisu
	$picsdir = "images/";
	$allfiles = array_slice(scandir($picsdir), 2);                          // nr 2 lõpus on scandiriga loetud kaks esimest kirjet, mis räägivad lihtsalt kataloogist, seega need ei ole pildid
	                                                                        // echo $allfiles[5];
	                                                                        // var_dump($allfiles);
	$allowedphototypes = ["image/jpeg", "image/png"];
	$picfiles = [];                                                         //tekitan listi

	foreach($allfiles as $file) {                                           // for tsükkel et leida vaid pildifailid allfilest ja siis tähista iga võetud fail $file. Tsükkel läbitakse niipalju kordi, kui me $allfilesis leidsime
		$fileinfo = getimagesize($picsdir .$file);                          // küsime faili suurust, sest selle abil saame me veel hunniku asju teada just sellelt pildilt mh failitüübi, mida meil vaja ongi
	                                                                        // var_dump($fileinfo); // edastab kogu info, et saame vaadata, mida meil kätte saada
		if(isset($fileinfo["mime"])) {                                      // kui nüüd fileinfos on "mime" siis edasi
			if(in_array($fileinfo["mime"], $allowedphototypes)) {           // kui arrays on mime ja kas ta on allowed... massiivis
				array_push($picfiles, $file);                               // array_push tähendab  võtan failime ja panen file picfiles massiivi
			}
		}
	}


	$photocount = count($picfiles);                                          // loeme üles piltide arvu
	$RandImgArray = [];                                                      // tekitan listi/massiivi, kuhu kogun alljärgnevalt 3 random pilti
	                                                                        // kasutame do while funktsiooni
	                                                                        // loome uue listi, kuhu lükkame meile sobivad pildid, st siis unikaalsed pildid. while counter leondab, palju seal listis pilte on
	do {
		$RandImg = mt_rand(0, $photocount-1);                               //leiame esimese suvapildi mt_rand funktsiooniga. Sulgudes on vahemik, millest milleni otsime.
		if(!(in_array($RandImg, $RandImgArray))) {                          //Kui pilti pole RandImgArray nimelises massiivis/listis, siis:
			array_push($RandImgArray, $RandImg);                            // lükkame pildi massiivi
		}
	} while (count($RandImgArray) < 3);                                     //käitame tsüklit 3 korda, ehk jooksutame, kuni RandImgArrayis on 3 pilti.

	                                                                        // nüüd defineerime, mis kohal RandImg listis mingi pilt täpselt on
	$randomphoto = $picfiles[$RandImgArray[0]];                             // seega randomfoto on pildifailide folderist pilt nr $RandImg
	$randomphoto2 = $picfiles[$RandImgArray[1]];
	$randomphoto3 = $picfiles[$RandImgArray[2]];

	// ver 2
	$randomphotofunc = array_rand($picfiles,3); 

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
		echo $todaysweekdayhtml;
		echo $todayname;
	?>

	<div class="row">
		<h2 class="col-12">Pildid keerulisel moel</h2>
		<div class="d-md-flex">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$randomphoto; ?>" alt="suvapilt"> <!-- echo järel ütlen, mis kataloogis, mis pilt asub -->
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$randomphoto2; ?>" alt="suvapilt">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$randomphoto3; ?>" alt="suvapilt">
		</div>
		<h2 class="col-12">Pildid funktsiooniga</h2>
		<div class="d-md-flex">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[0]]; ?>" alt="suvapilt">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[1]]; ?>" alt="suvapilt">
			<img class="img-fluid col-md-4 mb-3" src="<?php echo $picsdir .$picfiles[$randomphotofunc[2]]; ?>" alt="suvapilt">
		</div>
		<a class="col-12" href="https://github.com/jubejuss/githubactions/blob/main/kodutoo_01.php">Vaata koodi Githubist</a>
	</div>

<div>
	<a href="./">Aine kodulehele</a>
</div>
	<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script type="module" src="assets/js/starter.js"></script>  
</body>
</html>