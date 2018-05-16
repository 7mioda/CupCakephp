<?php
namespace App\FlashSale;

use App\FlashSale\Actions\FlashsaleAction;
use App\FlashSale\Actions\FlashsaleAdminAction;
use App\FlashSale\Actions\FlashsaleCRUDAction;
use App\FlashSale\Actions\OfferCRUDAction;
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
                      ->get("$prefix/venteflash",FlashsaleCRUDAction::class,'admin.flashsale.index');
            $container->get(Router::class)
                ->get("$prefix/venteflash/new",FlashsaleCRUDAction::class,'admin.flashsale.create');
            $container->get(Router::class)
                ->post("$prefix/venteflash/new",FlashsaleCRUDAction::class);
            $container->get(Router::class)
                ->get("$prefix/venteflash/{id:[0-9]+}",FlashsaleCRUDAction::class,'admin.flashsale.edit');
            $container->get(Router::class)
                ->post("$prefix/venteflash/{id:[0-9]+}",FlashsaleCRUDAction::class);
            $container->get(Router::class)
                ->delete("$prefix/venteflash/{id:[0-9]+}",FlashsaleCRUDAction::class,'admin.flashsale.delete');


            $container->get(Router::class)
                ->get("$prefix/offer",OfferCRUDAction::class,'admin.offer.index');
            $container->get(Router::class)
                ->get("$prefix/offer/new",OfferCRUDAction::class,'admin.offer.create');
            $container->get(Router::class)
                ->post("$prefix/offer/new",OfferCRUDAction::class);
            $container->get(Router::class)
                ->get("$prefix/offer/{id:[0-9]+}",OfferCRUDAction::class,'admin.offer.edit');
            $container->get(Router::class)
                ->post("$prefix/offer/{id:[0-9]+}",OfferCRUDAction::class);
            $container->get(Router::class)
                ->delete("$prefix/offer/{id:[0-9]+}",OfferCRUDAction::class,'admin.offer.delete');

        }

    }

}
