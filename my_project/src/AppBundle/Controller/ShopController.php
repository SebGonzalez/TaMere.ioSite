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
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        
    	$shop_repo = $this->getDoctrine()->getRepository('AppBundle:Object');
    	$weapons;
    	$skins;
    	$news;
        //On recupere l ensemble des armes, skins et nouveautes (2 dernieres semaines)
    	try{
    		$weapons = $shop_repo->getObjectsWithCategory(array(10));
    		$skins = $shop_repo->getObjectsWithCategory(array(9));
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
        //On verifie si la requete est bien une requete AJAX
        if(!$request->isXmlHttpRequest()){
            throw new BadRequestHttpException('AJAX request expected');
        }
        //Requete asynchrone pour ne pas bloquer le thread principal
        $response = new StreamedResponse();
        $response->setCallback(function() use ($request,$itemId){
            $session = $request->getSession();

            $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
            $user;
            //On recupere l utilisateur courant dans la base de donnees
            try{
                $user = $user_repo->findOneBy(array("userId" => $request->getSession()->get('id')));
            }catch(DBALException $e){
                echo "1";
                flush();
                return;
            }
            //On recupere l objet que l utilisateur souhaite acheter depuis la base de donnees
            $object_repo = $this->getDoctrine()->getRepository('AppBundle:Object');
            $object;
            try{
                $object = $object_repo->findOneBy(array("objectId" => $itemId));
            }catch(DBALException $e){
                echo "1";
                flush();
                return;
            }

            //On verifie si les credits de l utilisateur sont suffisants pour acheter l objet desire
            if($user->getUserCredit() < $object->getObjectPrice()){
                //Si c est le cas on retourne un erreur et on previent l utilisateur
                $json = ["message" => array("flash_warning","Vous n'avez pas assez de crédit")];
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                flush();
                return;
            }else{
                //Sinon on retire les credits depenses de l utilisateur
                $user->setUserCredit($user->getUserCredit() - $object->getObjectPrice());
                //On sauvegarde le nouvel achat de l utilisateur en base de donnees
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
                    //Si la sauvegarde echoue on renvoi une erreur
                    $json = ["message" => array("flash_error","Une erreur s'est produite")];
                    echo json_encode($json, JSON_UNESCAPED_UNICODE);
                    flush();
                    return;
                }
                //Si tout s est bien passe, on previent l utilisateur
                $json = ["message" => array("flash_validation","Votre achat a bien été ajouté à votre compte")];
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                flush();
            }
        });

        return $response;
    }
}
