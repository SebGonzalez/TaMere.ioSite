<?php
/**
 * Created by PhpStorm.
 * User: gaelvandecasteele
 * Date: 08/02/2018
 * Time: 15:14
 */

namespace AppBundle\Service;

//TO DO Ce service peur etre supprime

class ExampleService
{
    /**
     * @param string $controller
     * @return bool
     */
    public function has($controller)
    {
        list($class, $action) = explode('::', $controller, 2);
        return class_exists($class);
    }
}