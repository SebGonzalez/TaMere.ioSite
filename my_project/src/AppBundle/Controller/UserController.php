<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use Doctrine\DBAL\DBALException;

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

        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repo->findOneBy(array("userLogin" => $request->query->get('login')));
        if($user == null){
            return new Response('OK');
        }else{
            return new Response('Error');
        }
    }

    /**
     * @Route("/login/index",name="appbundle_login_home")
     */
    public function loginIndexAction()
    {
        return $this->render('User/index_login.html.twig');
    }

    /**
    * @Route("/login",name="appbundle_login")
    * @Method("Post")
    */
    public function loginAction(Request $request)
    {	
    	$login = $request->request->get('user_login');
    	$password = $request->request->get('user_password');
    	//Recuperer donner du formulaire

    	//Chercher l'utilisateur dans la base de donnee
    	$users = array(
    		array("login" => "lucas", "password" => "bonjour","firstname" => "lucas","lastname" => "Hochet"),
    		array("login" => "gael", "password" => "aurevoir","firstname" => "gael","lastname" => "Sarcas")
    	);

        $em = $this->getDoctrine()->getRepository('AppBundle:User');
        $foundDbUser = $em->findOneBy(array("userLogin" => $login, "userPassword" => hash('sha256',$password)));

        $session = $request->getSession();

        if($foundDbUser == null){
            $session->getFlashBag()->add('message','Nous n\'avons pas pu vous authentifier, veuillez vérifier vos informations');
            return $this->redirectToRoute('appbundle_login_home');
        }else{
            $session->set('loged',true);
            $session->set('login', $foundDbUser->getUserLogin());
            $session->set('firstname', $foundDbUser->getUserFirstName());
            $session->set('lastname', $foundDbUser->getUserLastName());
            $session->getFlashBag()->add('message','Authentification Réussie');

            return $this->redirectToRoute('appbundle_profil_edit');
        }
    }

    /**
    * @Route("/logout",name="appbundle_logout")
    */
    public function logoutAction(Request $request)
    {
    	$session = $request->getSession();
    	if($session->has('loged') != null && $session->get('loged') == true){
    		$session->invalidate();
    	}

    	$session->getFlashBag()->add('message','Vous avez bien été déconnecté');

    	return $this->redirectToRoute('homepage');
    }


    /**
    * @Route("/signup/index",name="appbundle_signup_index")
    */
    public function signUpIndexAction(Request $request){
        dump($request);
    	return $this->render('User/signup.html.twig');
    }

    /**
    * @Route("/signup",name="appbundle_signup")
    * @Method("Post")
    */
    public function signUpAction(Request $request){

        $file_transfer_service = $this->get('file_transfer');

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

    	//Si le mot de passe est incorrect
    	if($visitor_password !== $visitor_confirm_password){
    		$session->getFlashBag()->add('message','Les mots de passes ne sont pas identiques');
    		$this->redirectToRoute('appbundle_signup_index');
    	}
        //Si les adresses emails ne correspondent pas
        if($visitor_email !== $visitor_confirm_email){
            $session->getFlashBag()->add('message','Les adresses emails ne sont pas identiques');
            $this->redirectToRoute('appbundle_signup_index');
        }

        //Si l'utilisateur existe déjà en base de données
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(array("userLogin" => $visitor_login));

        if($user != null){
            $session->getFlashBag()->add('message','Ce login n\'est pas disponible');
            $this->redirectToRoute('appbundle_signup_index');
        }

    	//Si le login existe deja
    	if(@mkdir($_SERVER['DOCUMENT_ROOT'].'/users/'.$visitor_login) == false){
    		$session->getFlashBag()->add('message','Ce login existe déjà');
    		return $this->redirectToRoute('appbundle_signup_index');
    	}
    	//Si l'upload echoue
    	if($file_transfer_service->upload('avatar', '/users/'.$visitor_login.'/', array('jpg','png','jpeg'), 3145728) == false){
            rmdir($_SERVER['DOCUMENT_ROOT'].'/users/'.$visitor_login);
    		$session->getFlashBag()->add('message','L\'upload a echoue');
    		return $this->redirectToRoute('appbundle_signup_index');
    	}

        //Temporaire on prend le pays France
        $countryFrance = $this->getDoctrine()->
        getRepository('AppBundle:Country')->
        findOneBy(array('countryName' => 'France'));

        //Ajout de l'utilisateur a la base de données
        $newUser = new User();
        $newUser->setUserLogin($visitor_login)
        ->setUserFirstName($visitor_firstname)
        ->setUserLastName($visitor_lastname)
        ->setUserMail($visitor_email)
        ->setUserBirthday($visitor_birthdate)
        ->setUserGender($visitor_gender)
        ->setUserPassword(hash('sha256', $visitor_password))
        ->setUserSubscriptionDate(new \DateTime())
        ->setIdCountry($countryFrance);

        try{
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($newUser);
            $em->flush();
        }catch(DBALException $e){
            $this->getFlashBag()->add('message','An error occured');
            $this->redirectToRoute('appbundle_signup_index');
        }

    	$session->getFlashBag()->add('message','Votre compte a bien été crée');

    	return $this->redirectToRoute('appbundle_login_home');
    }

    /**
    *@Route("/profile/edit/index",name="appbundle_profil_edit")
    */
    public function editProfileIndexAction(){
        return $this->render('User/edit_profile.html.twig');
    }

    /**
    *@Route("/stats", name="appbundle_profil_stats")
    */
    public function statsIndexAction(){
        return $this->render('User/stats.html.twig');
    }
}
