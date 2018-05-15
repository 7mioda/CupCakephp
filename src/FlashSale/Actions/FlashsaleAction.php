<?php
namespace App\FlashSale\Actions;

use App\FlashSale\Repository\FlashSaleRepository;
use Cupcake\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;

class FlashsaleAction{
    private $renderer;
    private $flashrepo;

    public function __construct(Renderer $renderer,FlashSaleRepository $flashSaleRepository)
    {
        $this->renderer = $renderer;
        $this->flashrepo = $flashSaleRepository;
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
        return $this->renderer->render('@flashsale/index',compact('items'));
    }

    public function show(Request $request) {

        $item = $this->flashrepo->find($request->getAttribute('id'));
        return $this->renderer->render('@flashsale/show',compact('item'));
    }

}
