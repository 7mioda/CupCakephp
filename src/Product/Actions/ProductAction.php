<?php
namespace App\Product\Actions;

use App\Product\Repository\ProductRepository;
use Cupcake\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductAction{
    private $renderer;
    private $flashrepo;

    public function __construct(Renderer $renderer,ProductRepository $productRepository)
    {
        $this->renderer = $renderer;
        $this->flashrepo = $productRepository;
    }
    public function __invoke(Request $request)
    {
        if($request->getAttribute('id')){
            return $this->show($request);
        }else {
            return $this->index($request);
        }
    }

    public function index(Request $request)
    {
        $params = $request->getQueryParams();
        $items = $this->flashrepo->findPaginated(12,$params['p'] ?? 1);
        return $this->renderer->render('@product/index',compact('items'));
    }

    public function show(Request $request) {

        $item = $this->flashrepo->find($request->getAttribute('id'));
        return $this->renderer->render('@product/show',compact('item'));
    }

}
