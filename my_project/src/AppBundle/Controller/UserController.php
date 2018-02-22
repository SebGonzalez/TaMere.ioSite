<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

//include __DIR__.'\..\..\..\app\Resources\functions.php';

/**
*@Route("/user")
*/
class UserController extends Controller
{
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

    	$session = $request->getSession();

    	foreach($users as $uti){
    		if($uti['login'] == $login && $uti['password'] == $password){
    			$session->set('loged',true);
    			$session->set('login',$uti['login']);
    			$session->set('password',$uti['password']);
    			$session->set('firstname',$uti['firstname']);
    			$session->set('lastname',$uti['lastname']);
    			$session->getFlashBag()->add('message','Authentification Réussie');
    			return $this->redirectToRoute('homepage');
    		}
    	}

    	$session->getFlashBag()->add('message','Nous n\'avons pas pu vous authentifier, veuillez vérifier vos informations');
    	//S'il existe on cree l entite user et on le met dans la session et retour accueil

    	//Sinon redirige vers même page avec indication mauvais mot de passe

    	return $this->redirectToRoute('appbundle_login_home');
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

    	$session = $request->getSession();

    	//Si le mot de passe est incorrect
    	if($visitor_password !== $visitor_confirm_password){
    		$session->getFlashBag()->add('message','Les mots de passes ne sont pas identiques');
    		$this->redirectToRoute('appbundle_signup_index');
    	}

    	//Ajout de l utilisateur a la base de donnee

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

    	$session->getFlashBag()->add('message','Votre compte a bien été crée');

    	return $this->redirectToRoute('appbundle_login_home');
    }

    /**
    *@Route("/profile/edit/index",name="appbundle_profil_edit")
    */
    public function editProfileIndexAction(){
        return $this->render('User/edit_profile.html.twig');
    }
}
