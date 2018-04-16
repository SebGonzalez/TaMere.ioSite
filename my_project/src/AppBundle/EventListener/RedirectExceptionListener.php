<?php
namespace AppBundle\EventListener;

use AppBundle\Exception\RedirectException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class RedirectExceptionListener
{
	public function onKernelException(GetResponseForExceptionEvent $event){
		//Si l exception correspond bien a une exception de redirection, on redirige l utilisateur vers l url de l exception
		if($event->getException() instanceof RedirectException){
			$event->setResponse($event->getException()->getRedirectResponse());
		}
	}
}

?>