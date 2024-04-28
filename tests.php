<?php
	include("MongoDBHelper.php");

	$mongodb = new MongoDBHelper("localhost", 27017, "test", "test");

	#$mongodb->setDebug(1);

	$mongodb->insertDocument(["hallo" => "welt"]);

	$entries_in_db = $mongodb->find();

	print(count($entries_in_db)."\n");

	$i = 0;
	foreach ($entries_in_db as $key => $entry) {
		if($i != 0) {
			$mongodb->deleteEntry($entry['_id']['$oid']);
		}
		$i++;
	}

	$entries_in_db = $mongodb->find();

	print(count($entries_in_db)."\n");

	print($mongodb->count());
?>
