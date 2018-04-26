<?php
namespace Cupcake;
use Cupcake\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Class Router
 * Register and match Routes
 * @package Capcake
 */

class Router {
    /**
     * @var FastRouteRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param string $path
     * @param callable | string $callable
     * @param string $name
     */
    public function get(string $path,$callable ,?string $name= null){

        $this->router->addRoute(new ZendRoute($path,$callable,['GET'],$name));
    }

    /**
     * @param string $path
     * @param callable | string $callable
     * @param string $name
     */
    public function post(string $path,$callable ,?string $name= null){

        $this->router->addRoute(new ZendRoute($path,$callable,['POST'],$name));
    }

    /**
     * @param string $path
     * @param callable | string $callable
     * @param string $name
     */
    public function delete(string $path,$callable ,?string $name= null){

        $this->router->addRoute(new ZendRoute($path,$callable,['DELETE'],$name));
    }

    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request){

        $result = $this->router->match($request);
        if($result->isSuccess()){
            return new Route($result->getMatchedRouteName(),
                $result->getMatchedRoute()->getMiddleware(),
                $result->getMatchedParams()
            );
        }
        return null;

    }

    public function generateUri(string $name, array $params){
        return $this->router->generateUri($name,$params);
    }
}