<?php
	include("MongoDBHelper.php");

	$mongodb = new MongoDBHelper("localhost", 27017, "test", "test");

	$mongodb->setDebug(1);

	$mongodb->insertValue("1", "x", ["hallo" => "welt"]);
?>
