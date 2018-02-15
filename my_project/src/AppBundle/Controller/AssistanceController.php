<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AssistanceController extends Controller
{
    /**
     * @Route("/assistance", name="assistance")
     */
    public function indexAction()
    {
        return $this->render('secondary/assistance.html.twig');
    }
}
