<?php
namespace Cupcake;

use DI\Container;

class Renderer {

    private $loader;
    private $twig;

    public function __construct(string $path,Container $container)
    {
        $this->loader = new \Twig_Loader_Filesystem($path);
        $this->twig = new \Twig_Environment($this->loader,[
            'cache' => false,
            'debug'=>true,
        ]);
        if($container->has('twig.extension')){
            foreach($container->get('twig.extension') as $extension){
                $this->twig->addExtension($extension);
            }
        }
    }


    public function addPath(?string $path,string $namespace){
       $this->loader->addPath($path,$namespace);
    }

    public function render(string $view,array $params = []): string{

       return $this->twig->render($view.'.twig',$params);
    }

    public function addGlobal(string $key, $value):void{
        $this->twig->addGlobal($key,$value);
    }
}