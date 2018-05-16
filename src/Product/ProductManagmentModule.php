<?php
namespace App\Product;

use App\Product\Actions\CategoryCRUDAction;
use App\Product\Actions\ProductAction;
use App\Product\Actions\ProductCRUDAction;
use Cupcake\Module;
use Cupcake\Renderer;
use Cupcake\Router;
use Psr\Container\ContainerInterface;


class ProductManagmentModule extends Module {

    const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(ContainerInterface $container)
    {
        $prefix = $container->get('product.prefix');
        $container->get(Renderer::class)->addPath(__DIR__ . "/views",'product');
        $container->get(Router::class)->get($prefix,ProductAction::class,'product.index');
        $container->get(Router::class)->get($prefix.'/{id:[0-9]+}',ProductAction::class,'product.show');

        if($container->has('admin.prefix')){
            $prefix = $container->get('admin.prefix');
            $container->get(Router::class)
                      ->get("$prefix/product",ProductCRUDAction::class,'admin.product.index');
            $container->get(Router::class)
                ->get("$prefix/product/new",ProductCRUDAction::class,'admin.product.create');
            $container->get(Router::class)
                ->post("$prefix/product/new",ProductCRUDAction::class);
            $container->get(Router::class)
                ->get("$prefix/product/{id:[0-9]+}",ProductCRUDAction::class,'admin.product.edit');
            $container->get(Router::class)
                ->post("$prefix/product/{id:[0-9]+}",ProductCRUDAction::class);
            $container->get(Router::class)
                ->delete("$prefix/product/{id:[0-9]+}",ProductCRUDAction::class,'admin.product.delete');


            $container->get(Router::class)
                ->get("$prefix/category",CategoryCRUDAction::class,'admin.category.index');
            $container->get(Router::class)
                ->get("$prefix/category/new",CategoryCRUDAction::class,'admin.category.create');
            $container->get(Router::class)
                ->post("$prefix/category/new",CategoryCRUDAction::class);
            $container->get(Router::class)
                ->get("$prefix/category/{id:[0-9]+}",CategoryCRUDAction::class,'admin.category.edit');
            $container->get(Router::class)
                ->post("$prefix/category/{id:[0-9]+}",CategoryCRUDAction::class);
            $container->get(Router::class)
                ->delete("$prefix/category/{id:[0-9]+}",CategoryCRUDAction::class,'admin.category.delete');

        }

    }

}
