<?php
namespace App\FlashSale\Actions;

use App\FlashSale\Repository\FlashSaleRepository;
use Cupcake\Actions\RouterAwareAction;
use Cupcake\Renderer;
use Cupcake\Router;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;

class FlashsaleAdminAction{
    private $renderer;
    private $flashrepo;
    use RouterAwareAction;

    public function __construct(Container $container, Renderer $renderer,FlashSaleRepository $flashSaleRepository)
    {
        $this->renderer = $renderer;
        $this->router = $container->get(Router::class);
        $this->flashrepo = $flashSaleRepository;
    }
    public function __invoke(Request $request)
    {
        if($request->getMethod() === 'DELETE'){
            return $this->delete($request);
        }
        if(substr((string)($request->getUri()),-3)=== 'new'){
            return $this->create($request);
        }
        if($request->getAttribute('id')){
            return $this->edit($request);
        }else {
            return $this->index();
        }
    }

    public function index()
    {
        $items = $this->flashrepo->findAll();
        return $this->renderer->render('@flashsale/admin/index',compact('items'));
    }

    public function show(Request $request) {

        $item = $this->flashrepo->find($request->getAttribute('id'));
        return $this->renderer->render('@flashsale/admin/show',compact('item'));
    }
    public function edit(Request $request){

        $item = $this->flashrepo->find($request->getAttribute('id'));
        if($request->getMethod() === 'POST'){
            $params = array_filter($request->getParsedBody(),function($key){
                return in_array($key,['price','description']);
            },ARRAY_FILTER_USE_KEY);
            $this->flashrepo->update($item->id,$params);
            return $this->redirect("admin.flashsale.index");
         }
        $item = $this->flashrepo->find($request->getAttribute('id'));
        return $this->renderer->render('@flashsale/admin/edit',compact('item'));
    }

    public function create(Request $request){
        if($request->getMethod() === 'POST'){
            $params = array_filter($request->getParsedBody(),function($key){
                return in_array($key,['price','description']);
            },ARRAY_FILTER_USE_KEY);
            $this->flashrepo->insert($params);
            return $this->redirect("admin.flashsale.index");
        }
        return $this->renderer->render('@flashsale/admin/create',[]);

    }

    public function delete(Request $request){
        $this->flashrepo->delete($request->getAttribute('id'));
        return $this->redirect("admin.flashsale.index");

    }


}
