<?php
$rootFolder = $_SERVER['DOCUMENT_ROOT'];
require_once $rootFolder . '/qmarkets/classes/Search.php';

$status_code = '200';

$input = filter_input(INPUT_GET, 's');

if (strlen($input) > 2) {
	$result = Search::user($input);
} else {
	$result = 'Input should be more than 2 letters';
	$status_code = '400';
}

if ($result instanceof PDOException) {
	$result = $result->getMessage();
	$status_code = '400';
}

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
http_response_code($status_code);
echo json_encode($result);
