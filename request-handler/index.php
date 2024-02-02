<?php

try {

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once 'settings.php';
	require_once 'functions.php';
	// set_error_handler('myErrorHandler');


	// 1. ПОЛУЧАЕМ КЛЮЧ ИЗ POST
		$key = $_POST['key'];
	
	
	// 2. ПОДКЛЮЧАЕМ БД
		$db_config = 'host=' . MY_SERVER_NAME . ' port=' . DB_PORT . ' dbname=' . DB_NAME . ' user=' . DB_USER_NAME . ' password=' . DB_PASSWORD;
		$db_conn = pg_connect($db_config);

		if (!$db_conn) {
			log_errors('Не удалось подключиться к базе данных.');
			die();
		} else {
			$connection_status = pg_connection_status($db_conn);

			if ($connection_status === PGSQL_CONNECTION_BAD) {
				log_errors('Статус соединения: разорвано.');
				die();
			}
		}
		

	// 3. ВЫПОЛНЯЕМ SQL-ЗАПРОС
		$key	= pg_escape_string($key);
		$sql	= "SELECT * FROM testable WHERE key='{$key}'";
		$res	= pg_query($db_conn, $sql);
		$rows	= pg_fetch_all($res, PGSQL_ASSOC);


	if (!empty($rows)) {
		// 4. ПРОВЕРЯЕМ И ОПРЕДЕЛЯЕМ ЗАГОЛОВКИ СТОЛБЦОВ
			$num_fields = pg_num_fields($res);
			$headers = array();

			for ($i = 0; $i < $num_fields; $i++) {
				$field_name	= pg_field_name($res, $i);
				$headers[]	= $field_name;
			}

		// 5. УСТАНАВЛИВАЕМ ЗАГОЛОВКИ (для CSV файла)
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="data.csv"');

		// 6. ФОРМИРУЕМ ФАЙЛ CSV
			$file = fopen('php://output', 'w');
			fputcsv($file, $headers);
			while ($row = pg_fetch_assoc($res)) {
				fputcsv($file, $row);
			}
			fclose($file);

		pg_close($db_conn);

	} else {
		http_response_code(404);
	}



} catch (Exception $e) {
	log_errors('Ошибка: ' . $e->getMessage());

	http_response_code(500);
	echo 'Ошибка 500 - Внутренняя ошибка сервера';
}



