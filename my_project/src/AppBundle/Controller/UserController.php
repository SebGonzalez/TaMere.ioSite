<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
*@Route("/user")
*/
class UserController extends Controller
{
    /**
     * @Route("/login/index")
     */
    public function loginIndexAction()
    {
        return $this->render('AppBundle/Resources/views/User:loginIndex');
    }

}
