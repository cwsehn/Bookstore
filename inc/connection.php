<?php


try {
	//$db = new PDO("sqlite:".__DIR__."/databarrse.db");
	$dbMY = new PDO("mysql:host=localhost;dbname=fooThree;port=3306", "root", "ManyBlueCats11");
	$dbMY->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "<br/>";
	//var_dump($dbMY);

} catch (Exception $e) {
	echo "Unable to connect";
	echo $e->getMessage();
	exit;
}