<?php
  require("classes/SessionManager.class.php");
  //SessionManager::sessionStart("vr", 0, "/~juho.kalberg/", "tigu.hk.tlu.ee");
  SessionManager::sessionStart("vr", 0, "/", "localhost", false);
  
  //kas on sisse loginud
  if(!isset($_SESSION["user_id"])){
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