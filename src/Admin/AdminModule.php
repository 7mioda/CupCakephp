<?php
namespace App\Admin;
use Cupcake\Module;
use Cupcake\Renderer;

class AdminModule extends Module{

    const DEFINITIONS = __DIR__.'/config.php';

    public function __construct(Renderer $renderer)
    {
        $renderer->addPath(__DIR__.'/views','admin');
    }
}