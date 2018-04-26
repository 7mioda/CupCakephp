<?php
namespace Cupcake\Actions;

use GuzzleHttp\Psr7\Response;

trait RouterAwareAction{
    public function redirect(string $path, array $params = [])
    {
        $redirectUri = $this->router->generateUri($path,$params);
        return (new Response())
            ->withStatus(301)
            ->withHeader('location',$redirectUri);
    }
}