<?php
namespace App\Product\Actions;

use App\FlashSale\Entity\FlahSale;
use App\Product\Repository\CategoryRepository;
use App\Product\Repository\ProductRepository;
use Cupcake\Actions\CRUDAction;
use Cupcake\Renderer;
use Cupcake\Router;
use Cupcake\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductCRUDAction extends  CRUDAction {


    protected $viewPath = "@product/admin/Product";

    protected $routerPrefix = "admin.product";
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;


    public function __construct(Router $router,
                                Renderer $renderer,
                                ProductRepository $repository,
                                FlashService $flash,
                                CategoryRepository $categoryRepository)
    {
        parent::__construct($router, $renderer, $repository, $flash);
        $this->categoryRepository = $categoryRepository;
    }


    protected function getParams(Request $request): array
    {
        return array_filter($request->getParsedBody(),function($key){
            return in_array($key,['name','price','quantity','category']);}
            ,ARRAY_FILTER_USE_KEY);
    }

    protected function fromParams(array $params): array
    {
        $params['categories'] = $this->categoryRepository->findList();
        return $params;
    }

    protected function getValidator(Request $request){
        return parent::getValidator($request)
            ->required('name','price','quantity','category')
            ->exists('category',$this->categoryRepository->getTable(),$this->categoryRepository->getPdo());
    }

}
