<?php
namespace Cupcake;

use GuzzleHttp\Psr7\Response;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

Class App{
    /**
     * List of Modules
     * @var array
     */
    private $modules = [];
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * App constructor.
     * @param ContainerInterface $container
     * @param string[] $modules Listes des modules à charger
     * @internal param array $dependencies
     */
    public function  __construct(ContainerInterface $container,array $modules = [])
    {
        $this->container = $container;
        foreach ($modules as $module){
            $this->modules[] = $container->get($module);
        }
    }

    public function run(ServerRequestInterface $request) {
        $uri = $request->getUri()->getPath();
        $parsedbody = $request->getParsedBody();
        if(
            array_key_exists('_method',$parsedbody) &&
            in_array($parsedbody['_method'],['DELETE','PUT'])
        ){
            $request = $request->withMethod($parsedbody['_method']);
        }
        if(!empty($uri) && substr($uri,-1,1) === "/"){
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location',substr($uri,0,-1));
        }
            $router = $this->container->get(Router::class);
            $route = $router->match($request);
            if(is_null($route)){
                return new Response(404,[],'<h1>Erreur 404</h1>');
            }

            $params = $route->getParams();
            $request = array_reduce(array_keys($params), function($request,$key) use ($params){
               return $request->withAttribute($key,$params[$key]);
            },$request);
            $callback = $route->getCallback();
            if(is_string($callback)){
                $callback = $this->container->get($callback);
            }
            $response = call_user_func_array($callback,[$request]);

            if(is_string($response)){
                return new Response(200,[],$response);
            }elseif ($response instanceof ResponseInterface){
                return $response;
            }else{
                throw new \Exception('thes response is not a string or an instance of ResponseInterface');
            }
    }
}
