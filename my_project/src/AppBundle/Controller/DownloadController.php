<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DownloadController extends Controller
{
    /**
     * @Route("/download", name="download")
     */
    public function indexAction()
    {
        return $this->render('default/download.html.twig');
    }
}
