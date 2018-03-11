<?php
require '../vendor/autoload.php';

$app = new \Capcake\App();
$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);