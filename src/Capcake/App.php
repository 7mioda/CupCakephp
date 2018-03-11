<?php
namespace Capcake;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

Class App{
    /**
     * List of Modules
     * @var array
     */
    private $modules = [];

    /**
     * Router
     */
    private $router;

    /**
     * App constructor.
     * @param string[] $modules Listes des modules à charger
     */
    public function  __construct(array $modules = [])
    {
        $this->router = new Router();
        foreach ($modules as $module){
            $this->modules[] = new $module($this->router);
        }
    }

    public function run(ServerRequestInterface $request): ResponseInterface {
        $uri = $request->getUri()->getPath();
        if(!empty($uri) && substr($uri,-1,1) === "/"){
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location',substr($uri,0,-1));
        }
            $route = $this->router->match($request);
            if(is_null($route)){
                return new Response(404,[],'<h1>Erreur 404</h1>');
            }

            $response = call_user_func_array($route->getCallback(),[$request]);
            if(is_string($response)){
                return new Response(200,[],$response);
            }elseif ($response instanceof ResponseInterface){
                return $response;
            }else{
                throw new \Exception('thes response is not a string or an instance of ResponseInterface');
            }
    }
}
