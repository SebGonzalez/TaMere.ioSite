<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FAQController extends Controller
{
    /**
     * @Route("/FAQ", name="FAQ")
     */
    public function indexAction()
    {
        return $this->render('secondary/FAQ.html.twig');
    }
}
