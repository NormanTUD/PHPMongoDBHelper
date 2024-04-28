<?php
	include("MongoDBHelper.php");

	$mongodb = new MongoDBHelper("localhost", 27017, "test", "test");

	#$mongodb->setDebug(1);

	$mongodb->insertDocument(["hallo" => "welt"]);

	print_r($mongodb->find());
?>
