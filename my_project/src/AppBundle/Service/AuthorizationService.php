<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use AppBundle\Exception\RedirectException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthorizationService{
	private $session;
	private $request_stack;
	private $router;

	//Construit le service avec la session de l utilisateur, la pile de requetes et le routeur
	public function __construct(Session $session, RequestStack $stack, Router $router){
		$this->session = $session;
		$this->request_stack = $stack;
		$this->router = $router;
	}
	//Filtre pour laisser passer uniquement les utilisateurs connectes
	public function logedOnly(){
		//Dans le cas où l utilisateur est connecte on ressort de la fonction
		if($this->session->has('loged') && $this->session->get('loged') == true){
			return;
		}

		//Sinon on redirige l utilisateur vers la page de connexion avec l url a laquelle il essayait d acceder
		$url = $this->router->generate('appbundle_login_home');
		$url.= "?redirectUrl=".$this->request_stack->getMasterRequest()->getUri();
		throw new RedirectException(new RedirectResponse($url));
	}
}

?>