<?php

    require_once "../../../conf.php"; // conf.php fail on kolm kausta tagasi /veebirakendused kaustast
    
    // setlocale(LC_TIME, "et_ET");
    date_default_timezone_set('Europe/Tallinn');

    $news_output_error = null;
    $news_number = null;
        
        // kui submit nupp on vajutatud, kontrollib, et output error oleks null ja määrab $news_number väärtuse, milleks on lahtrisse sisestatud number
        if(isset($_POST["news_num_submit"])) {
            if(empty($news_output_error)) {
                $news_number = $_POST["news_output_num"];
            }
        }
    
    // funktsiooni parameeter asendatakse html-koodis oleva argumendiga, milleks on $news_number – ehk lahtriga sisestatud arv.
    function read_news($limit_to) {
        // echo $news_title .$news_content .$news_author;
        // echo $GLOBALS["server_host"]; // $GLOBALS commandiga saame deklaleerida global muutujaid, näiteks server_host
        // loome ühenduse andmebaasi serveri ja baasiga:
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
        // määrame suhtluseks kodeeringu:
        $conn -> set_charset("utf8");
        // valmistan ette SQL käsu:
        $stmt = $conn -> prepare("SELECT news_title, news_author, date_added, news_content, news_source FROM vr21news ORDER BY id DESC LIMIT ?;");// stmt nagu statement
        echo $conn -> error;
        // i-integer, s-string, d-decimal
        $stmt -> bind_result($news_title_from_db, $news_author_f_db, $date_added_f_db, $news_content_f_db, $news_source_f_db);
        $stmt -> bind_param("i", $limit_to); // bind_param loeb funktsiooni parameetri väärtust ($news_number) ja seob selle küsimärgiga            sql-querys. "i" kinnitab, et tegu on integeriga.
        $stmt -> execute();
        $raw_news_html = null; // raw, et oleks teine nimi võrreldes teise news_html muutujaga
        $date_of_news = new DateTime($date_added_f_db);
        $result = $date_of_news->format('d.M Y H:i:s');
        
        while ($stmt -> fetch()) {
            $raw_news_html .= "\n <h2>".$news_title_from_db ."</h2>";
            $raw_news_html .= "\n <h5>Lisatud: ".$date_added_f_db ."</h2>";

            $raw_news_html .= "\n <p>" .nl2br($news_content_f_db) ."</p>";
            $raw_news_html .= "\n <h5> Edastas: "; 
            if(!empty($news_author_f_db)) {
                $raw_news_html .= $news_author_f_db;
            } else {
                $raw_news_html .= "Anonüümne reporter";
            }
            $raw_news_html .= "</h5>";
            $raw_news_html .= "\n <h6>Allikas: ";
            if(!empty($news_source_f_db)) {
                $raw_news_html .= '<a href="'.$news_source_f_db.'">'.$news_source_f_db.'</a>';
            } else {
                $raw_news_html .= "Originaal";
            }
            $news_source_f_db ."</h6>";
        }
        $stmt -> close(); // lõpetame connectioni ära, kuna pole rohkem vaja
        $conn -> close();
        return $raw_news_html;
    }
    

?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
    <link rel="stylesheet" type="text/css" href="news.css">
</head>
<body>
	<h1>Uudiste lugemine</h1>
	<h3>See leht on valminud õppetöö raames.</h3>
    <p>Sisesta mitut uudist tahad korraga näha (1–10):</p>
    <form method="POST">
        <input type="number" min="1" max="10" value="1" name="news_output_num">
        <input type="submit" name="news_num_submit" value="Salvesta">
    </form>
	<hr>
    <p><?php
            echo $news_output_error; 
            echo read_news($news_number);
        ?></p>

</body>
</html>
