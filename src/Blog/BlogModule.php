<?php
namespace App\Blog;

use Capcake\Router;
use Psr\Http\Message\RequestInterface as Request;

class BlogModule {

    public function __construct(Router $router)
    {
        $router->get('/blog',[$this,'index'],'blog.index');
        $router->get('/blog/{slug:[a-z\-]+}',[$this,'show'],'blog.show');
    }
    public function index(Request $request): string{
        return '<h1>Bienvenue sur le blog</h1>';
    }
    public function show(Request $request): string{
        return '<h1>Bienvenue sur l\'article'.$request->getAttribute('slug').'</h1>';
    }
}
