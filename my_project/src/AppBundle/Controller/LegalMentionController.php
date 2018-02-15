<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LegalMentionController extends Controller
{
    /**
     * @Route("/Legal_Mention", name="legal mention")
     */
    public function indexAction()
    {
        return $this->render('secondary/LegalMention.html.twig');
    }
}
