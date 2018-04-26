<?php
namespace Cupcake\Router;


use Cupcake\Router;

class RouterTwigExtension extends \Twig_Extension
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path',[$this,'pathFor'])
        ];
    }

    public function pathFor(string $path,array $params=[]):string {

        return $this->router->generateUri($path,$params);
    }

}