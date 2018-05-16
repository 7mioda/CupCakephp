<?php
namespace Cupcake\Actions;

use Cupcake\Database\Repository;
use Cupcake\Renderer;
use Cupcake\Router;
use Cupcake\Session\FlashService;
use Cupcake\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;

class CRUDAction{
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var Repository
     */
    private $repository;
    /**
     * @var Router
     */
    private $router;

    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var string
     */
    protected $viewPath;

    /**
     * @var string
     */
    protected $routerPrefix;

    protected $messages = [
      'create' => "L'élément a bien été créé ",
      'edit' => "L'élément a bien été edité ",
      'delete' => "L'élément a bien été supprimé ",
    ];

    use RouterAwareAction;

    /**
     * FlashsaleAdminAction constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param Repository $repository
     * @param FlashService $flash
     * @internal param FlashSaleRepository $flashSaleRepository
     * @internal param SessionInterface $session
     */
    public function __construct(Router $router,
                                Renderer $renderer,
                                Repository $repository,
                                FlashService $flash)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->repository = $repository;
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
            return $this->index($request);
        }
    }

    /**
     * Affiche la liste des elements
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        $params = $request->getQueryParams();
        $items = $this->repository->findPaginated(12,$params['p'] ?? 1);
        return $this->renderer->render($this->viewPath.'/index',compact('items'));
    }

    /**
     * Afficher un element
     *
     * @param Request $request
     * @return string
     */

    public function show(Request $request) {

        $item = $this->repository->find($request->getAttribute('id'));
        return $this->renderer->render($this->viewPath.'/show',compact('item'));
    }

    /**
     * Editer un element
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait|string
     */
    public function edit(Request $request){

        $item = $this->repository->find($request->getAttribute('id'));
        if($request->getMethod() === 'POST'){
            $params = $this->getParams($request);
            $validator=$this->getValidator($request);
            if($validator->isValid()){
                $this->repository->update($item->id,$params);
                $this->flash->success($this->messages['edit']);
                return $this->redirect($this->routerPrefix.".index");
            }
            $errors = $validator->getErrors();
        }
        $item = $this->repository->find($request->getAttribute('id'));
        return $this->renderer->render($this->viewPath.'/edit',$this->fromParams(compact('item','errors')));
    }

    /**
     * Créer un element
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait|string
     */
    public function create(Request $request){
        $item = $this->getNewEntity();
        if($request->getMethod() === 'POST'){
            $params = $this->getParams($request);
            $validator=$this->getValidator($request);
            if($validator->isValid()) {
                $this->repository->insert($params);
                $this->flash->success($this->messages['create']);
                return $this->redirect($this->routerPrefix.".index");
            }
            $errors = $validator->getErrors();
        }
        return $this->renderer->render($this->viewPath.'/create',$this->fromParams(compact('errors')));

    }

    /**
     * Supprimer un element
     *
     * @param Request $request
     * @return \GuzzleHttp\Psr7\MessageTrait
     */

    public function delete(Request $request){
        $this->repository->delete($request->getAttribute('id'));
        return $this->redirect($this->routerPrefix.".index");

    }

    /**
     * Retourne les parametres a partir de la requete
     *
     * @param Request $request
     * @return array
     */
    protected function getParams(Request $request) : array
    {
        return array_filter($request->getParsedBody(),function($key){
            return in_array($key,['price','description']);}
            ,ARRAY_FILTER_USE_KEY);
    }

    /**
     * Génère le validateur pour valider les données
     *
     * @param Request $request
     * @return Validator
     */
    protected function getValidator(Request $request){
        return new Validator($request->getParsedBody());
    }

    /**
     * Génère une nouvelle entité pour l'action de création
     *
     * @return array
     */
    protected function getNewEntity()
    {
        return [];
    }

    /**
     * Permet de traiter les paramètres a envoyer a la vue
     *
     * @param $params
     * @return array
     */
    protected function fromParams(array $params) : array
    {
        return $params;
    }
}