<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use AppBundle\Entity\Purchase;

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

    /**
    * @Method({"GET"})
    * @Route("/shop/buy/{itemId}", name="appbundle_shop_buy", requirements={"itemId"="\d+"})
    */
    public function buyAction(Request $request, $itemId){

        if(!$request->isXmlHttpRequest()){
            throw new BadRequestHttpException('AJAX request expected');
        }

        $response = new StreamedResponse();
        $response->setCallback(function() use ($request,$itemId){
            $session = $request->getSession();

            $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
            $user;
            try{
                $user = $user_repo->findOneBy(array("userId" => $request->getSession()->get('id')));
            }catch(DBALException $e){
                echo "1";
                flush();
                return;
            }

            $object_repo = $this->getDoctrine()->getRepository('AppBundle:Object');
            $object;
            try{
                $object = $object_repo->findOneBy(array("objectId" => $itemId));
            }catch(DBALException $e){
                echo "1";
                flush();
                return;
            }

            if($user->getUserCredit() < $object->getObjectPrice()){
                $json = ["message" => "Vous n'avez pas assez de crédit"];
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                flush();
                return;
            }else{
                $user->setUserCredit($user->getUserCredit() - $object->getObjectPrice());
                try{
                    $em = $this->getDoctrine()->getEntityManager();
                    $purchase = new Purchase();
                    $purchase
                    ->setUser($user)
                    ->setObject($object)
                    ->setPurchasePrice($object->getObjectPrice())
                    ->setPurchaseDate(new \DateTime())
                    ;
                    $em->persist($purchase);
                    $em->flush();
                }catch(\Exception $e){
                    $json = ["message" => "Une erreur s'est produite"];
                    echo json_encode($json, JSON_UNESCAPED_UNICODE);
                    flush();
                    return;
                }
                $json = ["message" => "Votre achat a bien été ajouté à votre compte"];
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                flush();
            }
        });

        //return $this->redirectToRoute('appbundle_shop_home');

        return $response;
    }
}
