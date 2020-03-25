<?php

/**
* 
*/
/**
 * Class Router
 */
class Router
{
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']))
        {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

     public function run()
    {
        $uri = $this->getURI();
        if($uri == '') {
            $uri = 'site';
        }

        $segments = explode('/', $uri);

        $controllerName = array_shift($segments).'Controller';
        $controllerName = ucfirst("$controllerName");

        $action = ucfirst(array_shift($segments));
        $action = ($action) ? 'action'.$action : 'actionIndex';

        $parameters = $segments;
        $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            include ($controllerFile);
        }

        $controllerObject = new $controllerName;
        call_user_func_array(array($controllerObject, $action), $parameters);
    }


}