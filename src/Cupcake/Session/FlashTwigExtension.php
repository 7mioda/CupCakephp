<?php
namespace Cupcake\Session;

use Twig_SimpleFunction;

class FlashTwigExtension extends \Twig_Extension {

    /**
     * @var FlashService
     */
    private $flashService;

    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions() : array
    {
        return [
            new Twig_SimpleFunction('flash',[$this,'getFlash'])
        ];
    }

    public function getFlash($type): ?string
    {
        return $this->flashService->get($type);
    }
}