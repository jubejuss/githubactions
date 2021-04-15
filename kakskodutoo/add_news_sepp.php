<?php
// https://www.w3schools.com/php/php_form_validation.asp

    require_once "../../../conf.php"; // conf.php fail on kolm kausta tagasi /veebirakendused kaustast
    // echo $server_host;

// FORM VALIDATION: define variables and set to empty values. TRIM and STRIPSLASHES, et oleks loetavam ja poleks üleliigset jura.
$title = $content = $author = $source = "";
$titleErr = $contentErr = $authorErr = $sourceErr = "";
$news_input_error = null;
$author_null = null;
$source_null = null;
$news_input_success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = test_input($_POST["news_title_input"]);
  $content = test_input($_POST["news_content_input"]);
  $author = test_input($_POST["news_author_input"]);
  $source = test_input($_POST["news_input_source"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

/* FORM REQUIRED FIELDS: define variables and set to empty values.
https://www.w3schools.com/php/php_form_required.asp */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["news_title_input"])) {
    $titleErr = "Pealkiri on kohustuslik!";
    $news_input_error = "Uudise pealkiri on puudu! ";
  } else if (!preg_match("/^[^x-y]*$/",$title)) {
    $titleErr = "Võõrad sümbolid! Kasuta tähestiku tähti või numbreid!";
    $news_input_error = "Valed tähed! ";
  } else {
    $title = test_input($_POST["news_title_input"]);
  }

if (empty($_POST["news_content_input"])) {
    $contentErr = "Sisutekst on kohustuslik!";
    $news_input_error = "Uudise sisu on puudu! ";
  } else if (!preg_match("/^[^x-y]*$/",$content)) {
    $contentErr = "Võõrad sümbolid! Kasuta tähestiku tähti või numbreid!";
    $news_input_error = "Valed tähed! ";
  } else {
    $content = test_input($_POST["news_content_input"]);
  }

if (empty($_POST["news_author_input"])) {
    $author = "Anonüümne reporter";
  } else if (!preg_match("/^[a-zA-ZõäöüÕÄÖÜšŠžŽ\- ]*$/",$author)) {
    $authorErr = "* Võõrad sümbolid! Kirjuta nimi õigete karakteriga.";
    $news_input_error = "Valed tähed! ";
  } else {
    $author = test_input($_POST["news_author_input"]);
  }

if (empty($_POST["news_input_source"])) {
    $source = "Originaal";
  } else { 
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$source)) {
            $sourceErr = "Invalid URL";
            $news_input_error = "Valed tähed! ";
        } else { 
            $source = test_input($_POST["news_input_source"]);
         }
  }
}

/*
// $title = test_input($_POST["news_title_input"]);
if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$title)) {
  $titleErr = "Only letters and white space allowed";
  $news_input_error = "Valed tähed! ";
} else {
    $news_input_error = null;
}
// $content = test_input($_POST["news_content_input"]);
if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$content)) {
  $contentErr = "Only letters and white space allowed";
  $news_input_error = "Valed tähed! ";
} else {
    $news_input_error = null;
}
// $author = test_input($_POST["news_title_input"]);
if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$author)) {
  $authorErr = "Only letters and white space allowed";
}

//$source = test_input($_POST["news_input_source"]);
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$source)) {
  $sourceErr = "Invalid URL";
}
*/

    $news_title = null;
    // var_dump($_POST); // on olemas ka $_GET – näed viimati sisestatud vormi sisu arrayna
    if(isset($_POST["news_submit"])) {
        /* if(empty($_POST["news_title_input"])) {
            $news_input_error = "Uudise pealkiri on puudu! ";
        }
        if(empty($_POST["news_content_input"])) {
            $news_input_error .= "Uudise tekst on puudu!"; // .= märk lisab eelmisele väärtusele juurde. Kui eelmist pole, alustab uut
            $news_title = $_POST["news_title_input"];
        }*/
        if(empty($news_input_error)) {
            // salvestame andmebaasi
            store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"], $_POST['news_input_source']);
            $title = null;
            $content = null;
            $author = null;
            $source = null;
            $news_input_success = "Edukalt sisestatud!";
        }
   }

   function store_news($news_title, $news_content, $news_author, $news_source) {
       // echo $news_title .$news_content .$news_author;
       // echo $GLOBALS["server_host"]; // $GLOBALS commandiga saame deklaleerida global muutujaid, näiteks server_host
       // loome ühenduse andmebaasi serveri ja baasiga:
       $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]); //$GLOBALS seepärast, et conf.php andmed on funktsioonist väljas
       // määrame suhtluseks kodeeringu:
       $conn -> set_charset("utf8");
       // valmistan ette SQL käsu:
       $stmt = $conn -> prepare("INSERT INTO vr21news (news_title, news_content, news_author, news_source) VALUES (?, ?, ?, ?);");// Prepared statements in mysqli: https://www.w3schools.com/php/      php_mysql_prepared_statements.asp
       echo $conn -> error;
       // i-integer, s-string, d-decimal
       $stmt -> bind_param("ssss", $news_title, $news_content, $news_author, $news_source);  // küsimärkidega ??? seome mingi parameetri, mille määrame bind_param() funktsiooni abil. Vastupidine funktsioon oleks bind_result(), kust vastupidi tõmbame/loeme väärtuseid.
       //  The statement template can contain zero or more question mark (?) parameter markers⁠—also called placeholders. The parameter markers must be bound to application variables using mysqli_stmt_bind_param() before executing the statement. 
       $stmt -> execute();
       $stmt -> close(); // lõpetame connectioni ära, kuna pole rohkem vaja
       $conn -> close();
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
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud õppetöö raames.</p>
	<hr>
<!-- What is the $_SERVER["PHP_SELF"] variable?
             The $_SERVER["PHP_SELF"] is a super global variable that returns the filename of the currently executing script.

     What is the htmlspecialchars() function?
             The htmlspecialchars() function converts special characters to HTML entities. This means that it will replace HTML characters like < and  > with &lt; and &gt;. This prevents attackers from exploiting the code by injecting HTML or Javascript code (Cross-site Scripting attacks) in forms. -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="news_title_input">Uudise pealkiri</label>
        <br>
        <input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri" value="<?php echo $title; ?>">
        <span class="error star">* <?php echo $titleErr;?></span>
        <br><br>
        <label for="news_content_input">Uudise tekst</label>
        <br>
        <table>
        <tr valign="top">
          <td ><textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" rows="6" cols="40"><?php echo $content;?></textarea></td>
          <td><span class="error star">* <?php echo $contentErr;?></span></td></tr>
        </table>
        <br>
        <label for="news_author_input">Uudise autor</label>
        <br>
        <input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi" value="<?php if (empty($_POST["news_author_input"])) {
                                                                        echo $author_null;
                                                                        } else { echo $author; } ?>">
        <span class="error star"><?php echo $authorErr;?></span>
        <br><br>
        <label for="news_input_source">Uudise allikas (web)</label>
        <br>
        <input type="url" id="news_input_source" name="news_input_source" placeholder="Allikas" value="<?php if (empty($_POST["news_input_source"])) {
                                                                        echo $source_null;
                                                                        } else { echo $source; } ?>">
        <br><br>
        <input type="submit" name="news_submit" value="Salvesta uudis">
    </form>
    <br>
    <!-- <p><?php echo $news_input_error; ?></p>-->
    <p class="success"><?php echo $news_input_success; ?></p>

</body>
</html>

