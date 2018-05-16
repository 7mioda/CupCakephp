<?php
namespace App\FlashSale\Actions;

use App\FlashSale\Entity\FlahSale;
use App\FlashSale\Repository\OfferRepository;
use App\Product\Repository\ProductRepository;
use Cupcake\Actions\CRUDAction;
use Cupcake\Renderer;
use Cupcake\Router;
use Cupcake\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;

class OfferCRUDAction extends  CRUDAction {


    protected $viewPath = "@flashsale/admin/Offer";

    protected $routerPrefix = "admin.offer";
    /**
     * @var ProductRepository
     */
    private $productRepository;


    public function __construct(Router $router,
                                Renderer $renderer,
                                OfferRepository $repository,
                                FlashService $flash,
                                ProductRepository $productRepository)
    {
        parent::__construct($router, $renderer, $repository, $flash);
        $this->productRepository = $productRepository;
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

    protected function fromParams(array $params): array
    {
        $params['products'] = $this->productRepository->findList();
        return $params;
    }


}
