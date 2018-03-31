<?php
ini_set("display_errors","on");
if(!isset($dbh)){	
	session_start();	
 date_default_timezone_set("UTC"); // Set Time Zone
 $host = "mysql.hostinger.web.tr"; // Hostname
 $port = "3306"; // MySQL Port : Default : 3306
 $user = "u720087383_task"; // Username Here
 $pass = "123456t"; //Password Here
 $db   = "u720087383_task"; // Database Name
 $dbh  = new PDO('mysql:dbname='.$db.';host='.$host.';port='.$port,$user,$pass);
 
 /*Change The Credentials to connect to database.*/
 include("user_online.php");
}
?>
