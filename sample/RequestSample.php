<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use RequestService\Request;

$config = [
	'your-service' => [
		'url' => 'https://jsonplaceholder.typicode.com',
	],
];

$sample = new Request($config);
$response = $sample->sendRequest(
	'your-service',
	'GET',
	'todos/1'
);

print_r($response);
