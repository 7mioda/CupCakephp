<?php
namespace App\FlashSale\Actions;

use App\FlashSale\Entity\FlahSale;
use App\FlashSale\Repository\FlashSaleRepository;
use Cupcake\Actions\CRUDAction;
use Cupcake\Renderer;
use Cupcake\Router;
use Cupcake\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;

class FlashsaleCRUDAction extends  CRUDAction {


    protected $viewPath = "@flashsale/admin/FlashSale";

    protected $routerPrefix = "admin.flashsale";


    public function __construct(Router $router, Renderer $renderer, FlashSaleRepository $repository, FlashService $flash)
    {
        parent::__construct($router, $renderer, $repository, $flash);
    }


    protected function getParams(Request $request): array
    {
        return array_filter($request->getParsedBody(),function($key){
            return in_array($key,['price','description']);}
            ,ARRAY_FILTER_USE_KEY);
    }


    protected function getValidator(Request $request){
        return parent::getValidator($request)
            ->required('price','description')
            ->length('description',5);
    }

    protected function getNewEntity()
    {
        $flashsale = new FlahSale();
        $flashsale->date = new \DateTime();
        return $flashsale;
    }


}
