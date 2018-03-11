<?php
require '../vendor/autoload.php';

$app = new \Capcake\App([
    BlogMdule::class
]);
$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);