<?php

use Cupcake\Renderer;
use Cupcake\Session\FlashTwigExtension;
use Cupcake\Session\PHPSession;
use Cupcake\Session\SessionInterface;
use Cupcake\Twig\PagerFantaExtension;
use Cupcake\Twig\RouterTwigExtension;
use Cupcake\Twig\TextExtension;
use Cupcake\Twig\TimeExtension;
use function DI\{get,object};

return [
    'database.host'=> 'localhost',
    'database.username'=> 'root',
    'database.password'=> '123456789',
    'database.name'=> 'CupCake',
    'views.path'=>dirname(__DIR__).'/views',
    'twig.extension'=>[
      get(RouterTwigExtension::class),
      get(FlashTwigExtension::class),
      get(TimeExtension::class),
      get(TextExtension::class),
      get(PagerFantaExtension::class),
      new Twig_Extension_Debug()
    ],
    SessionInterface::class =>object(PHPSession::class),
    \Cupcake\Router::class => \DI\object(\Cupcake\Router::class),
    Renderer::class => object(Renderer::class)->constructorParameter('path',get('views.path')),
    \PDO::class => function(\DI\Container $container){
       return new PDO(
        'mysql:host='.$container->get('database.host').';dbname='.$container->get('database.name'),
          $container->get('database.username'),
          $container->get('database.password'),
           [
               PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
           ]
    );
}
];