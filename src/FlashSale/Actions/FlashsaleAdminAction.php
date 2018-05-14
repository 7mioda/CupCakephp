<?php
namespace App\FlashSale\Actions;

use App\FlashSale\Repository\FlashSaleRepository;
use Cupcake\Actions\RouterAwareAction;
use Cupcake\Renderer;
use Cupcake\Router;
use Cupcake\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface as Request;

class FlashsaleAdminAction{
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var FlashSaleRepository
     */
    private $flashrepo;
    /**
     * @var Router
     */
    private $router;

    /**
     * @var FlashService
     */
    private $flash;
    use RouterAwareAction;

    /**
     * FlashsaleAdminAction constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param FlashSaleRepository $flashSaleRepository
     * @param FlashService $flash
     * @internal param SessionInterface $session
     */
    public function __construct(Router $router,
                                Renderer $renderer,
                                FlashSaleRepository $flashSaleRepository,
                                FlashService $flash)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->flashrepo = $flashSaleRepository;
        $this->flash = $flash;
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

    /**
     * Afficher toutes les vente flash en adminstration
     * @return string
     */
    public function index()
    {
        $items = $this->flashrepo->findAll();
        return $this->renderer->render('@flashsale/admin/index',compact('items'));
    }

    /**
     * Afficher une unique venteflash
     *
     * @param Request $request
     * @return string
     */

    public function show(Request $request) {

        $item = $this->flashrepo->find($request->getAttribute('id'));
        return $this->renderer->render('@flashsale/admin/show',compact('item'));
    }

    /**
     * Editer une vente flash
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait|string
     */
    public function edit(Request $request){

        $item = $this->flashrepo->find($request->getAttribute('id'));
        if($request->getMethod() === 'POST'){
            $params = array_filter($request->getParsedBody(),function($key){
                return in_array($key,['price','description']);
            },ARRAY_FILTER_USE_KEY);
            $this->flashrepo->update($item->id,$params);
            $this->flash->success("La vente flash a été bien modifiée");
            return $this->redirect("admin.flashsale.index");
         }
        $item = $this->flashrepo->find($request->getAttribute('id'));
        return $this->renderer->render('@flashsale/admin/edit',compact('item'));
    }

    /**
     * Créer une vente flash
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait|string
     */
    public function create(Request $request){
        if($request->getMethod() === 'POST'){
            $params = array_filter($request->getParsedBody(),function($key){
                return in_array($key,['price','description']);
            },ARRAY_FILTER_USE_KEY);
            $this->flashrepo->insert($params);
            $this->flash->success("La vente flash a été bien ajoutée");
            return $this->redirect("admin.flashsale.index");
        }
        return $this->renderer->render('@flashsale/admin/create',[]);

    }

    /**
     * Supprimer une vente flash
     *
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait
     */

    public function delete(Request $request){
        $this->flashrepo->delete($request->getAttribute('id'));
        return $this->redirect("admin.flashsale.index");

    }


}
