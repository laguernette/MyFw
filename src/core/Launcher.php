<?php

namespace MyFW\Core;

use \Illuminate\Database\Capsule\Manager as Capsule;

class Launcher
{

    public static function run(Request $r)
    {

        $env = new Context($r);

        if ($r->getController() == 'rest') {
            //Si c'est un controller Rest, on ajoute l'action dans le nom
            $controllerName = '\MyFW\App\Controllers\Controller' . ucfirst($r->getController()) . ucfirst($r->getAction());
            $methodName = 'defaut';
        } else {
            //Si ce n'est pas un controller Rest
            $controllerName = '\MyFW\App\Controllers\Controller' . ucfirst($r->getController());
            $methodName = $r->getAction();
        }
        if (class_exists($controllerName)) {
            // instanciation du contrôleur
            // Request en paramètre
            $controller = new $controllerName($env);
            if (method_exists($controller, $methodName)) {
                if (isset($r->getArguments()['uri'])) {
                    // avec paramètres
                    call_user_func_array(array($controller, $methodName), $r->getArguments()['uri']);
                } else {
                    // sans paramètres
                    call_user_func(array($controller, $r->getAction()));
                }
            } else {
                echo 'Action not present';
            }
            //
            //echo '<hr>';
            //print_r($app);
        } else {
            echo 'Controller not present';
        }
    }

}