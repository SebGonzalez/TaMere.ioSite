<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use AppBundle\Entity\Personalization;
use AppBundle\Entity\Purchase;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

//include __DIR__.'\..\..\..\app\Resources\functions.php';

/**
*@Route("/user")
*/
class UserController extends Controller
{
    /**
    * @Route("/checkLoginExistence")
    */
    public function checkLoginExistence(Request $request){
        //Fonction pour requete AJAX
        //On cherche l utilisateur passe en parametre de la requete, si il exite deja on renvoi une erreur sinon OK
        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repo->findOneBy(array("userLogin" => $request->query->get('login')));
        if($user == null){
            return new Response('OK');
        }else{
            return new Response('Error');
        }
    }

    /**
    * @Route("/getProbableUsers")
    */
    public function getProbableUsers(Request $request){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();

        //Fonction pour requete AJAX
        //On chercher les utilisateurs qui pourraient correspondre au login passe en parametre et on les renvoi

        $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $alike_users_array = $user_repo->getProbableUsers($request->query->get('login'));

        return new JsonResponse($alike_users_array);
    }

    /**
     * @Route("/login/index",name="appbundle_login_home")
     */
    public function loginIndexAction(Request $request)
    {
        //On Affiche la page de connexion avec une eventuelle URL de redirection
        $redirectUrl = $request->query->get('redirectUrl');
        return $this->render('User/index_login.html.twig', array("redirectUrl"=>$redirectUrl));
    }

    /**
    * @Route("/login",name="appbundle_login")
    * @Method("Post")
    */
    public function loginAction(Request $request)
    {	
        $redirectUrl = $request->query->get('redirectUrl');
        //On recupere les donnees du formulaire
    	$login = $request->request->get('user_login');
    	$password = $request->request->get('user_password');

        //On recupere l utilisateur qui correspond au login passe au formulaire
        $em = $this->getDoctrine()->getRepository('AppBundle:User');
        $foundDbUser = $em->findOneBy(array("userLogin" => $login, "userPassword" => hash('sha256',$password)));

        $session = $request->getSession();

        //Si cet utilisateur n existe pas on previent l utilisateur et on renvoi l eventuelle URL de redirection si elle existe
        if($foundDbUser == null){
            $session->getFlashBag()->add('warning','Veuillez vérifier vos informations');
            //TO DO add redirect url
            $login_home_url = $this->generateUrl('appbundle_login_home');
            if($redirectUrl != null)
                $login_home_url.="?redirectUrl=".$redirectUrl;
            return $this->redirect($login_home_url);
        }else{
            //Sinon on rempli les variables de session avec les informations de l utilisateur qui vient de se connecter
            $session->set('loged',true);
            $session->set('id', $foundDbUser->getUserId());
            $session->set('login', $foundDbUser->getUserLogin());
            $session->set('firstname', $foundDbUser->getUserFirstName());
            $session->set('lastname', $foundDbUser->getUserLastName());
            $session->set('avatar', $foundDbUser->getUserAvatarPath());
            $session->getFlashBag()->add('information','Authentification Réussie');

            //Si une URL de redirection existe on redirige l utilisateur vers la page a laquelle il essayait d acceder sinon on le redirige vers son profil
            if($redirectUrl != null)
                return $this->redirect($redirectUrl);
            else
                return $this->redirectToRoute('appbundle_profil_index');
        }
    }

    /**
    * @Route("/logout",name="appbundle_logout")
    */
    public function logoutAction(Request $request)
    {
        //On verifie que l utilisateur est bien connecte avant de proceder a la deconnexion puis on detruit sa session, on le previent de la reussite de la deconnexion et on le renvoi vers la page d accueil
    	$session = $request->getSession();
    	if($session->has('loged') != null && $session->get('loged') == true){
    		$session->invalidate();
    	}

    	$session->getFlashBag()->add('information','Vous avez bien été déconnecté');

    	return $this->redirectToRoute('homepage');
    }


    /**
    * @Route("/signup/index",name="appbundle_signup_index")
    */
    public function signUpIndexAction(Request $request){
        //On affiche la page d inscription
    	return $this->render('User/signup.html.twig');
    }

    /**
    * @Route("/signup",name="appbundle_signup")
    * @Method("Post")
    */
    public function signUpAction(Request $request){
        //On recupere le service qui sert a uploader des fichiers vers le serveur
        $file_transfer_service = $this->get('file_transfer');
        //On recupere toutes les informations du formulaire
    	$visitor_login = $request->request->get('visitor_login');
    	$visitor_password = $request->request->get('visitor_password');
    	$visitor_confirm_password = $request->request->get('visitor_confirm_password');
    	$visitor_firstname = $request->request->get('visitor_firstname');
    	$visitor_lastname = $request->request->get('visitor_lastname');
        $visitor_email = $request->request->get('visitor_email');
        $visitor_confirm_email = $request->request->get('visitor_confirm_email');
        $visitor_birthdate = new \DateTime($request->request->get('visitor_birthdate'));
        $visitor_gender = ($request->request->get('visitor_gender') === 'male') ? true : false;

    	$session = $request->getSession();

    	//Si le mot de passe ne correspond pas avec le mot de passe de confirmation on previent l utilisateur et on le renvoi vers la page d inscription
    	if($visitor_password !== $visitor_confirm_password){
    		$session->getFlashBag()->add('warning','Les mots de passes ne sont pas identiques');
    		return $this->redirectToRoute('appbundle_signup_index');
    	}
        //Si les adresses emails ne correspondent pas on previent l utilisateur et on le renvoi vers la page d inscription
        if($visitor_email !== $visitor_confirm_email){
            $session->getFlashBag()->add('warning','Les adresses emails ne sont pas identiques');
            return $this->redirectToRoute('appbundle_signup_index');
        }

        //Si l'utilisateur existe déjà en base de données, ceci est deja verifie par du Javascript cote utilisateur, on previent l utilisateur et on le renvoi vers la page d inscription
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(array("userLogin" => $visitor_login));

        if($user != null){
            $session->getFlashBag()->add('warning','Ce login n\'est pas disponible');
            return $this->redirectToRoute('appbundle_signup_index');
        }

    	//Si le dossier de l utilisateur existe deja ou une erreur s est produite on previent l utilisateur et on le renvoi vers la page d inscription sinon on cree son dossier utilisateur
    	if(mkdir($this->getParameter('user_directory').$visitor_login) == false){
    		$session->getFlashBag()->add('warning','Ce login existe déjà');
    		return $this->redirectToRoute('appbundle_signup_index');
    	}
    	//Si l'upload de la photo de profil de l utilisateur echoue, on supprime son dossier utilisateur, on previent l utilisateur et on le redirige vers la page d inscription, sinon la photo est uploade et on continue
    	if($file_transfer_service->upload('avatar', 'users/'.$visitor_login.'/', array('jpg','png','jpeg'), 3145728) == false){
            rmdir($this->getParameter('web_directory').'users/'.$visitor_login);
    		$session->getFlashBag()->add('error','L\'upload a echoue');
    		return $this->redirectToRoute('appbundle_signup_index');
    	}

        //Pour l instant le choix du pays n est pas encore implemente, le pays par defaut est la France
        $countryFrance = $this->getDoctrine()->
        getRepository('AppBundle:Country')->
        findOneBy(array('countryName' => 'France'));

        //On ajoute l utilisateur a la base de donnees, son mot de passe est hashe avec l algorithme SHA256
        $newUser = new User();
        $newUser->setUserLogin($visitor_login)
        ->setUserFirstName($visitor_firstname)
        ->setUserLastName($visitor_lastname)
        ->setUserMail($visitor_email)
        ->setUserBirthday($visitor_birthdate)
        ->setUserGender($visitor_gender)
        ->setUserPassword(hash('sha256', $visitor_password))
        ->setUserSubscriptionDate(new \DateTime())
        ->setUserCredit(0)
        ->setUserAvatarPath($_FILES["avatar"]["name"])
        ->setIdCountry($countryFrance);

        //On persiste l utilisateur en base de donnees
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($newUser);
            $em->flush();
        }catch(\Exception $e){
            //Si une erreur survient on previent l utilisateur et on le redirige vers la page d inscription
            $this->addFlash('error','Une erreur s\'est produite durant la creation de votre compte');
            return $this->redirectToRoute('appbundle_signup_index');
        }

        //On recupere l arme par defaut et le skin par defaut de la base de données
        $object_repo = $this->getDoctrine()->getRepository('AppBundle:Object');
        $defaultSkin = $object_repo->findOneBy(array("objectId"=>9));
        $defaultWeapon = $object_repo->findOneBy(array("objectId"=>10));
        //On cree deux nouveaux achats pour l utilisateur, le skin par defaut et l arme par defaut, on ajoute le prix d achat, la date d achat 
        $newDefaultSkinPurchase = new Purchase();
        $newDefaultSkinPurchase->setUser($newUser);
        $newDefaultSkinPurchase->setObject($defaultSkin);
        $newDefaultSkinPurchase->setPurchasePrice($defaultSkin->getObjectPrice());
        $newDefaultSkinPurchase->setPurchaseDate(new \DateTime());

        $newDefaultWeaponPurchase = new Purchase();
        $newDefaultWeaponPurchase->setUser($newUser);
        $newDefaultWeaponPurchase->setObject($defaultWeapon);
        $newDefaultWeaponPurchase->setPurchasePrice($defaultWeapon->getObjectPrice());
        $newDefaultWeaponPurchase->setPurchaseDate(new \DateTime());
        //On cree deux nouvelles personalisations, avec l arme par defaut et le skin par defaut
        $newSkinPersonalization = new Personalization();
        $newSkinPersonalization->setUserId($newUser);
        $category_repo = $this->getDoctrine()->getRepository('AppBundle:ObjectCategory');
        $skin_category = $category_repo->findOneBy(array("objectCategoryId"=>9));
        $newSkinPersonalization->setObjectCategoryId($skin_category);
        $newSkinPersonalization->setObjectId($defaultSkin);

        $newWeaponPersonalization = new Personalization();
        $newWeaponPersonalization->setUserId($newUser);
        $weapon_category = $category_repo->findOneBy(array("objectCategoryId"=>10));
        $newWeaponPersonalization->setObjectCategoryId($weapon_category);
        $newWeaponPersonalization->setObjectId($defaultWeapon);

        //On persiste le tout en base de donnees
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($newDefaultSkinPurchase);
            $em->persist($newDefaultWeaponPurchase);
            $em->persist($newSkinPersonalization);
            $em->persist($newWeaponPersonalization);
            $em->flush();
        }catch(\Exception $e){
            $this->addFlash('error','An error occured during category creation');
            return $this->redirectToRoute('appbundle_signup_index');
        }

        //On previent l utilisateur de la reussite de la creation de son compte
    	$session->getFlashBag()->add('validation','Votre compte a bien été crée');

    	return $this->redirectToRoute('appbundle_login_home');
    }

    /**
    *@Route("/profile/edit/index",name="appbundle_profil_edit_index")
    */
    public function editProfileIndexAction(Request $request){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        //On recupere les donnees a jour de l utilisateur connecte et on le redirige vers la page d edition de son profil
        $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $user_repo->findOneBy(array("userId"=>$request->getSession()->get('id')));

        return $this->render('User/edit_profile.html.twig', array("user"=>$user));
    }

    /**
    *@Route("/profile/edit", name="appbundle_profil_edit")
    */
    public function editProfileAction(Request $request){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();

        $session = $request->getSession();
        //On recupere l utilisateur connecte a jour
        $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $user_repo->findOneBy(array('userId'=>$request->getSession()->get('id')));
        //On recupere les donnnees du formulaire et on sauvegarder les informations qui ont ete modifiees
        $edited_email = $request->request->get('edit_profile_email');
        if($user->getUserMail() != $edited_email) $user->setUserMail($edited_email);
        $edited_password = $request->request->get('edit_profile_password');
        if($user->getUserPassword() != hash('sha256',$edited_password) && $edited_password != "") $user->setUserPassword(hash('sha256',$edited_password));
        $edited_firstname = $request->request->get('edit_profile_firstname');
        if($user->getUserFirstName() != $edited_firstname) $user->setUserFirstName($edited_firstname);
        $edited_lastname = $request->request->get('edit_profile_lastname');
        if($user->getUserLastName() != $edited_email) $user->setUserLastName($edited_lastname);
        $edited_gender = $request->request->get('edit_profile_gender');
        if($user->getUserGender() != ($edited_gender == 'male') ? true : false) $user->setUserGender(($edited_gender == 'male') ? true : false);
        $edited_birthday = $request->request->get('edit_profile_birthdate');
        if($user->getUserBirthday() != new \DateTime($edited_birthday)) $user->setUserBirthday(new \DateTime($edited_birthday));
        //Si une nouvelle photo a ete uplode on l a met a jour en base de donnees et dans la session
        if($_FILES['avatar']['error'] !== 4){
            if(file_exists($this->getParameter('web_directory').'users/'.$request->getSession()->get('login').basename($_FILES['avatar']["name"]))){
                unlink($this->getParameter('web_directory').'users/'.$request->getSession()->get('login').'/'.basename($_FILES['avatar']["name"]));
            }
            $this->get('file_transfer')->upload('avatar', 'users/'.$user->getUserLogin().'/', array('jpg','png','jpeg'), 3145728);
            if(file_exists($this->getParameter('web_directory').'users/'.$user->getUserLogin().'/'.$user->getUserAvatarPath())){
                unlink($this->getParameter('web_directory').'users/'.$user->getUserLogin().'/'.$user->getUserAvatarPath());
            }
            $user->setUserAvatarPath(basename($_FILES['avatar']["name"]));
            $request->getSession()->set('avatar', basename($_FILES['avatar']["name"]));
        }
        //On sauvegarde le tout
        try{
            $this->getDoctrine()->getManager()->flush();
        }catch(\Exception $e){
            $request->getSession()->getFlashBag()->add('error','Une erreur s\'est produite');
            return $this->redirectToRoute('appbundle_profil_edit_index');
        }
        //On previent l utilisateur
        $request->getSession()->getFlashBag()->add('validation','Vos informations ont été mises à jour');

        return $this->redirectToRoute('appbundle_profil_index');
    }

    /**
    *@Route("/stats/{userLogin}", name="appbundle_profil_stats", defaults={"userLogin"=""})
    */
    public function statsIndexAction(Request $request, $userLogin){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        //On recupere l utilisateur et ses informations a jour ou ceux d un autre utilisateur
        $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user;
        if($userLogin != ""){
            $user = $user_repo->findOneBy(array("userLogin"=>$userLogin));
        }else{
            $user = $user_repo->findOneBy(array("userId"=>$request->getSession()->get('id')));
        }
        //On recupere les informations de statistiques
        $user_count = $user_repo->getUserCount();
        $stat_repo = $this->getDoctrine()->getRepository('AppBundle:Stat');
        $user_rank = $stat_repo->getUserRank($user->getUserId());
        $user_ranks = $stat_repo->getUsersRank();
        $user_stats = $stat_repo->getUserStatsWithObjects($user->getUserId());

        //On affiche la page des statistiques avec toutes les informations
        return $this->render('User/stats.html.twig', array("user"=>$user,"nb_user"=>$user_count, "user_rank"=>$user_rank,"users_rank"=>$user_ranks, "weaponStats"=>$user_stats));
    }

    /**
    *@Route("/profile/index", name="appbundle_profil_index")
    */
    public function profileIndexAction(Request $request){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        //On recupere les informations de l utilisateur a jour, ses achats et on affiche son profil
        $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $user_repo->findOneBy(array("userId" => $request->getSession()->get('id')));
        $purchase_repo = $this->getDoctrine()->getRepository('AppBundle:Purchase');
        $purchases = $purchase_repo->getUserPurchasesWithObjects($request->getSession()->get('id'));

        return $this->render('User/profile_index.html.twig', array('purchases' => $purchases, 'user' => $user));
    }

    /**
    *@Route("/reloadcredits/index", name="appbundle_reloadcredits_index")
    */

    public function reloadCreditsIndexAction(){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        //On affiche la page de rechargement des credits
        return $this->render('User/reload_credits_index.html.twig');
    }

    private function reloadCredits(int $amount,Request $request){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        //On recupere l utilisateur et ses informations a jour, on credite son compte de nombre de credit achete
        $user_repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $user_repo->findOneBy(array("userId" => $request->getSession()->get('id')));
        $user->setUserCredit($user->getUserCredit() + $amount);
        try{
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();
        }catch(DBALException $e){
            $this->getFlashBag()->add('error','An error occured');
            $this->redirectToRoute('appbundle_signup_index');
        }
        //On previent l utilisateur que les credits ont bien ete ajoutes a son compte
        $request->getSession()->getFlashBag()->add('validation',$amount.' Crédits ont bien été ajoutés à votre compte');
    }

    /**
    *@Route("/reloadcredit/250", name="appbundle_reloadcredits_250")
    */
    public function reload250CreditsAction(Request $request){
        //On recharge 250 credits
        $this->reloadCredits(250,$request);
        return $this->redirectToRoute('appbundle_reloadcredits_index');
    }
    /**
    *@Route("/reloadcredit/750", name="appbundle_reloadcredits_750")
    */
    public function reload750CreditsAction(Request $request){
        //On recharge 750 credits
        $this->reloadCredits(750,$request);
        return $this->redirectToRoute('appbundle_reloadcredits_index');
    }
    /**
    *@Route("/reloadcredit/2000", name="appbundle_reloadcredits_2000")
    */
    public function reload2000CreditsAction(Request $request){
        //On recharge 2000 credits
        $this->reloadCredits(2000,$request);
        return $this->redirectToRoute('appbundle_reloadcredits_index');
    }
    /**
    *@Route("/reloadcredit/5000", name="appbundle_reloadcredits_5000")
    */
    public function reload5000CreditsAction(Request $request){
        //On recharge 5000 credits
        $this->reloadCredits(5000,$request);
        return $this->redirectToRoute('appbundle_reloadcredits_index');
    }

    /**
    *@Route("/skinedition/index", name="appbundle_skinedition_index")
    */
    public function skinEditionIndexAction(Request $request){
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();
        //On recupere la personalisation de skin et d arme de l utilisateur connecte
        $session = $request->getSession();
        $personalization_repo = $this->getDoctrine()->getRepository('AppBundle:Personalization');
        $skinPersonalization = $personalization_repo->findOneBy(array("userId" => $session->get('id'),"objectCategoryId"=>9));
        $weaponPersonalization = $personalization_repo->findOneBy(array("userId" => $session->get('id'),"objectCategoryId"=>10));
        //On recupere les achat d armes et de skins de l utilisateur
        $purchase_repo = $this->getDoctrine()->getRepository('AppBundle:Purchase');
        $skinPurchases = $purchase_repo->getUserPurchasesWithObjects($request->getSession()->get('id'), 9);
        $weaponPurchases = $purchase_repo->getUserPurchasesWithObjects($request->getSession()->get('id'), 10);
        //On recupere les categories d objet
        $categories_repo = $this->getDoctrine()->getRepository('AppBundle:ObjectCategory');
        $categories = $categories_repo->findAll();

        return $this->render('User/skin_edition_index.html.twig',array("skinPersonalization" => $skinPersonalization,"weaponPersonalization"=>$weaponPersonalization, "skinPurchases"=>$skinPurchases,"weaponPurchases" => $weaponPurchases, "categories"=>$categories));
    }

    /**
    *@Method("GET")
    *@Route("/skinedition/change/{skinId}/{weaponId}", name="appbundle_skinedition_change")
    */
    public function saveSkinModifications(Request $request, $skinId, $weaponId){
        /*if(!$request->isXmlHttpRequest()){
            throw new BadRequestHttpException('AJAX request expected');
        }*/
        //Fonction pour requete AJAX
        $access_service = $this->get('authorization_service');
        $access_service->logedOnly();

        $user_id = $request->getSession()->get('id');
        //On verifie si les objets qui nous parviennent de la requete AJAX sont bien en possession de l utilisateur connecte, si c est le cas on continue, sinon on renvoi une erreur
        $purchase_repo = $this->getDoctrine()->getRepository('AppBundle:Purchase');
        $skin = $purchase_repo->findOneBy(array("user"=>$user_id, "object"=>$skinId));
        $weapon = $purchase_repo->findOneBy(array("user"=>$user_id, "object"=>$weaponId));
        if($skin == null || $weapon == null)
            return new JsonResponse(["message" => array("flash_error","Une erreur s'est produite")]);

        //On recupere les personnalisations de skin et d arme courante de l utilisateur connecte
        $personalization_repo = $this->getDoctrine()->getRepository('AppBundle:Personalization');
        $skinPersonalization = $personalization_repo->findOneBy(array("userId"=>$user_id, "objectCategoryId"=>9));
        $weaponPersonalization = $personalization_repo->findOneBy(array("userId"=>$user_id, "objectCategoryId"=>10));
        //On modifie le skin et l arme courante
        $skinPersonalization->setObjectId($skin->getObject());
        $weaponPersonalization->setObjectId($weapon->getObject());
        //On sauvegarde les modifications en base de donnees
        $this->getDoctrine()->getManager()->flush();
        //On retourne la confirmation de sauvegarde au code Javascript
        $json = ["message" => array("flash_validation","Les modification ont été sauvegardées")];
        return new JsonResponse($json);
    }
}
