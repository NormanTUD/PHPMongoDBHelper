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

	$replace_first_id = $entries_in_db[0]["_id"]['$oid'];

	$mongodb->replaceDocument($replace_first_id, ["x" => ["y" => 1]]);

	$entries_in_db = $mongodb->find();
	print(json_encode($entries_in_db));

	print($mongodb->count());
?>
