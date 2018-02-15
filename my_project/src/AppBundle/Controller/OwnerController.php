<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OwnerController extends Controller
{
    /**
     * @Route("/owner", name="owner")
     */
    public function indexAction()
    {
        return $this->render('secondary/owner.html.twig');
    }
}
