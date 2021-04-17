<?php
  require("classes/SessionManager.class.php");
  SessionManager::sessionStart("vr", 0, "/~juho.kalberg/", "tigu.hk.tlu.ee");
  
  //kas on sisse loginud
  if(!isset($_SESSION["userid"])){
	//jõuga suunatakse sisselogimise lehele
	header("Location: page.php");
	exit();
  }
  
  //logime välja
  if(isset($_GET["logout"])){
	//lõpetame sessiooni
	session_destroy();
	//jõuga suunatakse sisselogimise lehele
	header("Location: page.php");
	exit();
  }