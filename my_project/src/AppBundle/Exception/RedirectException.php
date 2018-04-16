<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpFoundation\RedirectResponse;

/*
* Une classe d'exception pour rediriger les utilisateurs hors des controlleurs
*/
class RedirectException extends \Exception
{
	//La reponse de redirection
	private $redirectResponse;

	public function __construct(RedirectResponse $redirectResponse, $message = '', $code = 0, \Exception $previousException = null){
		$this->redirectResponse = $redirectResponse;
		parent::__construct($message, $code, $previousException);
	}

	public function getRedirectResponse(){
		return $this->redirectResponse;
	}
}