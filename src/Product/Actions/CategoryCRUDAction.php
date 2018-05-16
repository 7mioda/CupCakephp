<?php
namespace App\Product\Actions;

use App\Product\Repository\CategoryRepository;
use Cupcake\Actions\CRUDAction;
use Cupcake\Renderer;
use Cupcake\Router;
use Cupcake\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryCRUDAction extends  CRUDAction {


    protected $viewPath = "@product/admin/Category";

    protected $routerPrefix = "admin.category";


    public function __construct(Router $router, Renderer $renderer, CategoryRepository $repository, FlashService $flash)
    {
        parent::__construct($router, $renderer, $repository, $flash);
    }


    protected function getParams(Request $request) : array
    {
        return array_filter($request->getParsedBody(),function($key){
            return in_array($key,['percentage','product','price']);}
            ,ARRAY_FILTER_USE_KEY);
    }


    protected function getValidator(Request $request){
        return parent::getValidator($request)
            ->required('product','price');
    }

    protected function getNewEntity()
    {
        $flashsale = new FlahSale();
        $flashsale->date = new \DateTime();
        return $flashsale;
    }

    protected function fromParams($params): array
    {
        $params['products'] = [];
    }


}
