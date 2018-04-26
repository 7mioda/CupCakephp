<?php
namespace App\FlashSale;

use App\FlashSale\Actions\FlashsaleAction;
use App\FlashSale\Actions\FlashsaleAdminAction;
use Cupcake\Module;
use Cupcake\Renderer;
use Cupcake\Router;
use Psr\Container\ContainerInterface;


class FlashSaleModule extends Module {

    const DEFINITIONS = __DIR__.'/config.php';

    public function __construct(ContainerInterface $container)
    {
        $prefix = $container->get('flashsale.prefix');
        $container->get(Renderer::class)->addPath(__DIR__."/views",'flashsale');
        $container->get(Router::class)->get($prefix,FlashsaleAction::class,'flashsale.index');
        $container->get(Router::class)->get($prefix.'/{id:[0-9]+}',FlashsaleAction::class,'flashsale.show');

        if($container->has('admin.prefix')){
            $prefix = $container->get('admin.prefix');
            $container->get(Router::class)
                      ->get("$prefix/venteflash",FlashsaleAdminAction::class,'admin.flashsale.index');
            $container->get(Router::class)
                ->get("$prefix/venteflash/new",FlashsaleAdminAction::class,'admin.flashsale.create');
            $container->get(Router::class)
                ->post("$prefix/venteflash/new",FlashsaleAdminAction::class);
            $container->get(Router::class)
                ->get("$prefix/venteflash/{id:[0-9]+}",FlashsaleAdminAction::class,'admin.flashsale.edit');
            $container->get(Router::class)
                ->post("$prefix/venteflash/{id:[0-9]+}",FlashsaleAdminAction::class);
            $container->get(Router::class)
                ->delete("$prefix/venteflash/{id:[0-9]+}",FlashsaleAdminAction::class,'admin.flashsale.delete');
        }

    }

}
