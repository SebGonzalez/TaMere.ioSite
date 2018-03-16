<?php

namespace AppBundle\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    /**
     * @Route("/shop", name="appbundle_shop_home")
     */
    public function indexAction()
    {
    	$shop_repo = $this->getDoctrine()->getRepository('AppBundle:Object');
    	$weapons;
    	$skins;
    	$news;

    	try{
    		$weapons = $shop_repo->getObjectsWithCategory(array(6));
    		$skins = $shop_repo->getObjectsWithCategory(array(1,2,3));
    		$news = $shop_repo->findByUploadDateRangeWithCategory((new \DateTime())->sub(new \DateInterval('P2W')), new \DateTime());
    	}catch(DBALException $e){

    	}

        return $this->render('Shop/index_shop.html.twig', array('weapons' => $weapons, 'skins' => $skins, 'news' => $news));
    }
}
