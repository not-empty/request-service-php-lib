<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use RequestService\Request;

$config = [
	'your-service' => [
		'url' => 'https://developer.marvel.com/',
		'json' => false,
	],
];

$sample = new Request($config);
$header = [
	'stream' => true,
];

$response = $sample->sendRequest(
	'your-service',
	'GET',
	'docs',
	$header
);

print_r($response);
