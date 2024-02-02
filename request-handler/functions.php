<?php

function log_errors($error) {
	$file = 'error.log';
	$message = date('Y-m-d H:i:s') . ' - ' . $error . PHP_EOL;
	file_put_contents($file, $message, FILE_APPEND);
}

function printToDebug($arr, $var_dump = false) {
	
	// $arr_json = addslashes(json_encode($arr));
	$arr_json = json_encode($arr);
	$arr_json = str_replace("'", "\'", $arr_json);

	$php_code = '$arr_json=' . "'" . $arr_json ."'" . ';' . PHP_EOL;
	$php_code .= '$arr=json_decode($arr_json, true);' . PHP_EOL;
	$php_code .= 'echo "<pre>";' . PHP_EOL;

	if ($var_dump) {
		$php_code .= 'var_dump($arr);' . PHP_EOL;
	} else {
		$php_code .= 'print_r($arr);' . PHP_EOL;
	}

	$php_code .= 'echo "</pre>";' . PHP_EOL;

	file_put_contents('debug.php', $php_code, FILE_APPEND);
}
