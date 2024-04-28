<?php
	function green_text ($str) {
		return "\033[32m".$str."\033[0m";
	}

	function red_text ($str) {
		return "\033[31m".$str."\033[0m";
	}

	$GLOBALS['started_tests'] = 0;
	function print_diffs ($name, $a, $b) {
		$message = "ERROR: $name failed! Expected (".red_text(gettype($a))."):\n====>\n".
			red_text(print_r($b, true))."\n<====\ngot (".red_text(gettype($b))."):\n====>\n".
			red_text(print_r($a, true))."\n<====\n";
		return $message;
	}

	function increate_started_tests () {
		if(array_key_exists('started_tests', $GLOBALS)) {
			$GLOBALS['started_tests'] = $GLOBALS['started_tests'] + 1;
		} else {
			$GLOBALS['started_tests'] = 1;
		}
	}

	function is_equal ($name, $a, $b) {
		increate_started_tests();
		if(gettype($a) == gettype($b)) {
			if(gettype($a) == 'string') {
				if($a == $b) {
					print green_text("OK").": $name\n";
					return 1;
				} else {
					$message = print_diffs($name, $a, $b);
					trigger_error($message, E_USER_WARNING);;
					test_failed();
				}
			} else {
				if (serialize($a) == serialize($b)) {
					print green_text("OK").": $name\n";
					return 1;
				} else {
					$message = print_diffs($name, $a, $b);
					trigger_error($message, E_USER_WARNING);
					test_failed();
				}
			}
		} else {
			print print_diffs($name, $a, $b);
			trigger_error($message, E_USER_WARNING);
			test_failed();
		}
		return 0;
	}

	function is_unequal ($name, $a, $b) {
		increate_started_tests();
		if(!gettype($a) == gettype($b)) {
			print green_text("OK").": $name\n";
			return 1;
		} else {
			if(gettype($a) == gettype($b)) {
				if(gettype($a) == 'string') {
					if($a == $b) {
						$message = print_diffs($name, $a, $b);
						trigger_error($message, E_USER_WARNING);;
						test_failed();
					} else {
						print green_text("OK").": $name\n";
						return 1;
					}
				} else {
					if (serialize($a) == serialize($b)) {
						$message = print_diffs($name, $a, $b);
						trigger_error($message, E_USER_WARNING);;
						test_failed();
					} else {
						print green_text("OK").": $name\n";
						return 1;
					}
				}
			} else {
				print print_diffs($name, $a, $b);
				trigger_error($message, E_USER_WARNING);;
				test_failed();
			}
		}
		return 0;
	}

	function regex_matches ($name, $string, $regex) {
		increate_started_tests();
		if(gettype($string) == 'integer' || gettype($string) == 'float') {
			$string = (string) $string;
		}
		if(gettype($string) == 'string') {
			if(preg_match($regex, $string)) {
				print green_text("OK").": $name\n";
				return 1;
			} else {
				$message = "ERROR: $name failed! Expected:\n====>\n".
					red_text($string)."\n<===\nto match\n====>\n".
					red_text($regex)."\n<====\n";
				trigger_error($message, E_USER_WARNING);;
				test_failed();
			}
		} else {
			$message = "Expected ====>\n$string\n<====\n to be string, not ".red_text(gettype($string));
			trigger_error($message, E_USER_WARNING);;
			test_failed();
		}
		return 0;
	}

	function regex_fails ($name, $string, $regex) {
		increate_started_tests();
		if(gettype($string) == 'integer' || gettype($string) == 'float') {
			$string = (string) $string;
		}
		if(gettype($string) == 'string') {
			if(preg_match($regex, $string)) {
				$message = "ERROR: $name failed! Expected\n:\n====>\n".
					red_text($string)."\n<===\nNOT to match\n====>\n".
					red_text($regex)."\n<====\n";
				trigger_error($message, E_USER_WARNING);;
				test_failed();
			} else {
				print green_text("OK").": $name\n";
				return 1;
			}
		} else {
			$message = "Expected ====>\n$string\n<====\n to be string, not ".red_text(gettype($string));
			trigger_error($message, E_USER_WARNING);;
			test_failed();
		}
		return 0;
	}


	function test_failed () {
		if(array_key_exists('failed_tests', $GLOBALS)) {
			$GLOBALS['failed_tests'] = $GLOBALS['failed_tests'] + 1;
		} else {
			$GLOBALS['failed_tests'] = 1;
		}
	}

	function is_equal_safe ($name, $a, $b) {
		if($a == $b) {
			print green_text("OK").": $name\n";
			return 1;
		} else {
			test_failed();
			red_text("!!! BASIC TEST FAILED!!! SOMETHING HAS GONE HORRIBLY WRONG WITH THE TESTING FRAMEWORK!!!\n");
			return 0;
		}
	}

	register_shutdown_function('shutdown');

	function shutdown () {
		done_testing();
	}

	function done_testing() {
		if($GLOBALS['started_tests']) {
			print "\n".green_text("Number of started tests: ".$GLOBALS['started_tests'])."\n";
		} else {
			print red_text("Seemingly no tests done!")."\n";
		}

		if(isset($GLOBALS['failed_tests'])) {
			print red_text("Failed tests: ".$GLOBALS['failed_tests'])."\n";
			exit(1);
		}
	}


	include("MongoDBHelper.php");

	$mongodb = new MongoDBHelper("localhost", 27017, "test", "test");

	#$mongodb->setDebug(1);

	regex_matches("insertDocument", $mongodb->insertDocument(["hallo" => "welt"]), "/success/");

	$entries_in_db = $mongodb->find();

	is_equal("testing \$mongodb->find() after insertDocument", count($entries_in_db), 2);

	$i = 0;
	foreach ($entries_in_db as $key => $entry) {
		if($i != 0) {
			$mongodb->deleteEntry($entry['_id']['$oid']);
		}
		$i++;
	}

	$entries_in_db = $mongodb->find();

	is_equal("count(\$entries_in_db)", count($entries_in_db), 1);

	$replace_first_id = $entries_in_db[0]["_id"]['$oid'];

	$mongodb->replaceDocument($replace_first_id, ["x" => ["y" => 1]]);

	$entries_in_db = $mongodb->find();
	regex_matches('json_encode($entries_in_db)', json_encode($entries_in_db), '/\[\{"_id":\{"\$oid":"[a-z0-9]*"\},"hallo":"welt","x":\{"y":1\}\}\]/');

	is_equal('$mongodb->count()', $mongodb->count(), 1);
?>
