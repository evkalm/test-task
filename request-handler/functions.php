<?php

function log_errors($error) {
	$file = 'error.log';
	$message = date('Y-m-d H:i:s') . ' - ' . $error . PHP_EOL;
	file_put_contents($file, $message, FILE_APPEND);
}
