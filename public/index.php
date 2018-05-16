<?php

use App\Admin\AdminModule;
use App\Product\ProductManagmentModule;
use Cupcake\App;
use App\FlashSale\FlashSaleModule;
require '../vendor/autoload.php';

//error_reporting( E_ALL );
//ini_set( "display_errors", 1 );

$modules = [
    AdminModule::class,
    ProductManagmentModule::class,
    FlashSaleModule::class
];

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__).'/config/config.php');
foreach ($modules as $module) {
    if($module::DEFINITIONS){
        $builder->addDefinitions($module::DEFINITIONS);
    }
}
$builder->addDefinitions(dirname(__DIR__).'/config.php');
$container = $builder->build();

$renderer = $container->get(\Cupcake\Renderer::class);
$app = new App($container,$modules);
$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
\Http\Response\send($response);